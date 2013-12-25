<?php

/**
 * Администрирование пользователей
 *
 * @author irina
 */
class UserAdmin {

    const ADMIN = 1;
    const USER = 2;
    const GUEST = 3;
    const MESSAGERECIPIENT = 4;
    const VKONTAKTE = 'vk';
    const FACEBOOK = 'fb';
    const TWITTER = 'tw';
    const MAILRU = 'mr';
    const RECOVER_PASS_DIR = '/site/recoverPassword';
    const UNREGISTERED_USER = 'Незарегистрированный пользователь';
    const FACEBOOK_URL = 'https://graph.facebook.com/';
    const VK_URL = 'https://api.vkontakte.ru/method/getProfiles?uids=';
//    const TW_CONSUMER_KEY = 'rb0LycEOMXNzAzoh0iph6g';
//    const TW_CONSUMER_SECRET = 'trbZO3H3TbFqnE9HwMyHUwHBqViyn73RFJuDd0BpEwE';
    const TW_CONSUMER_KEY = '7Kq2QypUuygRG3aaxDfeVg';
    const TW_CONSUMER_SECRET = 'KhoBq2Gg2KnuOVCvgxC6QcRGJgDrJX1qh4vvfcSe4';
    const MAIL_RU_URL = 'http://www.appsmail.ru/platform/api';
    const MAIL_RU_APP_ID = '702170';
//    const MAIL_RU_PRIVATE_KEY = '46e46e4b31db8e46ebf05ba621578afd';
    const MAIL_RU_SECRET_KEY = '4873fe156fe26c05d36c8571e443988c';

    /**
     * Список существующих ролей
     * @return array
     */
    public static function getRoles() {

        return array(
            self::ADMIN => 'admin',
            self::USER => 'user',
            self::GUEST => 'guest',
            self::MESSAGERECIPIENT => 'message recipient',
        );
    }

    /**
     * Информация о юзере от facebook
     * @param string $id
     * @return array
     */
    public static function fbInfo($id) {

        $facebookUrl = self::FACEBOOK_URL . $id;
        $str = file_get_contents($facebookUrl);
        $result = json_decode($str);
        if (isset($result->error)) {
            return false;
        }

        return $result;
    }

    /**
     * Информация о юзере от vk
     * @param string $id
     * @return array
     */
    public static function vkInfo($id) {

        $vkurl = self::VK_URL . $id . '&fields=photo';
        $str = file_get_contents($vkurl);
        $result = json_decode($str);
        if (isset($result->error)) {
            return false;
        }

        $data = $result->response;
        return $data[0];
    }

    /**
     * Информация о юзере от twitter
     * @param string $id
     */
    public static function twInfo($oauthVerifier, $oauthToken, $oauthTokenSecret, $userId) {

        $connectionFirst = new TwitterOAuth(self::TW_CONSUMER_KEY, self::TW_CONSUMER_SECRET, $oauthToken, $oauthTokenSecret);
        $tokenCredentials = $connectionFirst->getAccessToken($oauthVerifier);        
        if(!$tokenCredentials){
            return false;
        }
        $connection = new TwitterOAuth(self::TW_CONSUMER_KEY, self::TW_CONSUMER_SECRET, $tokenCredentials['oauth_token'], $tokenCredentials['oauth_token_secret']);        
        $result = $connection->get('users/lookup', array('user_id' => $userId));        
        $userObject = $result[0];
        $names = split(' ', $userObject->name);
        $name = array(
            'first_name' => $names[0],
            'last_name' => isset($names[1]) ? $names[1] : null,
        );
        return (object) $name;
    }

    public static function mrInfo($sessionKey, $userId){
        $params = array(
            'method' => 'users.getInfo',
            'app_id' => self::MAIL_RU_APP_ID,
            'session_key' => $sessionKey,
            'uids' => $userId,
        );
        ksort($params);
        $query = '';
        foreach ($params as $key => $value){
            $query[] = $key.'='.$value;
        }
        $sig = md5(implode($query).self::MAIL_RU_SECRET_KEY);
        $query[] = 'sig=' . $sig;
        $url = self::MAIL_RU_URL . '?' . implode('&', $query);
        return $url;
    }

    /**
     * Регистрация через соц.сети
     * @param string $net_type
     * @param string $id
     * @return int
     */
    public static function createBySocialNet($net_type, $id = null, $data = null) {

        $user = new BRUser();
        $user->$net_type = $id;
        if ($net_type == self::FACEBOOK) {
            $result = self::fbInfo($id);
        } elseif ($net_type == self::VKONTAKTE) {

            $result = self::vkInfo($id);
        } elseif ($net_type == self::TWITTER) {

            $result = self::twInfo($data->oauth_verifier, $data->oauth_token, $data->oauth_token_secret, $id);
        } elseif ($net_type == self::MAILRU) {
            $result = self::mrInfo($data->session_key, $id);
            return $result;
        }

        if (!$result) {
            return false;
        }
        $user->password = md5('');
        $user->first_name = $result->first_name;
        $user->last_name = $result->last_name;
        $user->role = self::USER;
        $user->created = date('Y-m-d H:i:s', time());
        if ($user->validate()) {
            $user->save();
            return $user;
        }
        return false;
    }

    /**
     * Вход через социальные сети
     * @param string $net_type
     * @param int $id
     * @return boolean
     */
    public static function signInBySocialNet($net_type, $id = null, $data = null) {

        $user = User::model()->findByAttributes(array($net_type => $id));
        $signup = 0;
        if (!$user) {
            $user = self::createBySocialNet($net_type, $id, $data);
            return $user;
            $signup = 1;
        }
        if ($user) {
            $res = array('user_id' => $user->id, 'signup' => $signup);
            return (object) $res;
        }
        return false;
    }

    /**
     * Заполнение полей юзера
     * @param User $user
     * @param object $data
     * @return boolean
     */
    public static function bindData($user, $data) {

        $userFields = UserField::model()->findAll();
        foreach ($userFields as $field) {

            $userData = new UserData();
            $attribute = $field->name;
            if (isset($data->$attribute)) {

                $userData->attributes = array(
                    'user_id' => $user->id,
                    'field_id' => $field->id,
                    'value' => strtolower($data->$attribute),
                );
                $userData->save();
            } elseif ($field->required) {

                return false;
            }
        }

        return true;
    }

    /**
     * Регистрация
     * @param User $newUserData
     * @return object
     */
    public static function signUp($newUserData) {

        $user = new BRUser();

        foreach ($user->attributes as $attribute => $value) {
            if (isset($newUserData->$attribute)) {
                $user->$attribute = strtolower($newUserData->$attribute);
            }
        }

        $user->role = self::USER;
        $user->created = date('Y-m-d H:i:s', time());
        $user->password = md5($newUserData->password);

        if ($user->validate()) {

            if ($user->save() && self::bindData($user, $newUserData)) {

                $result = array('error' => false, 'user' => $user);
                return (object) $result;
            }
        }
        $result = array('error' => true, 'user' => $user->getErrors());
        return (object) $result;
    }

    /**
     * Авторизация
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function authorization($email, $password) {

        return BRUser::model()->findByAttributes(array('email' => $email, 'password' => md5($password)));
    }

    /**
     * Генерация пароля
     * @param User $user
     * @return string
     */
    public static function generatePassword($user) {

        return substr(md5(time() . $user->email . rand(1, 1000)), 0, 8);
    }

    /**
     * Отправка временного пароля
     * @param User $user
     * @return boolean
     */
    public static function sendTempPassword($user, $password) {

        $subject = 'Рупор.ру: Восстановление пароля';
        $message = 'Ваш новый пароль: ' . $password . '. Пожалуйста, не забудьте сменить его в целях безопасности.';
        $mail = new BRMail($user->email, $subject, $message);
        return $mail->send();
    }

    /**
     * Создание временного пароля для юзера
     * @param User $user
     */
    public static function saveTempPassword($user) {

        $password = self::generatePassword($user);
        $user->password = md5($password);
        if ($user->update()) {
            self::sendTempPassword($user, $password);
        }
    }

    /**
     * Восстановление пароля
     * @param string $key
     * @param string $newPassword
     * @return boolean
     */
    public static function recoverPassword($key, $newPassword) {

        $user = User::model()->findByAttributes(array('password' => md5($key)));
        if ($user) {

            $user->password = md5($newPassword);
            $user->update();
            return $user;
        }
        return false;
    }

    /**
     * Изменение профайла юзера
     * @param User $user
     * @param object $data
     * @return object
     */
    public static function editProfile($user, $data) {

        foreach ($user->attributes as $attribute => $value) {

            if (isset($data->$attribute)) {

                $user->$attribute = $data->$attribute;
            }
        }
        if (!$user->validate()) {
            $result = array('error' => true, 'user' => $user);
        } else {
            $user->update();
            UserAdmin::editUserField($user->id, $data);
            $result = array('error' => false, 'user' => $user);
        }
        return (object) $result;
    }

    /**
     * Подписка на обновление заявки
     * @param int $userId
     * @param int $requestId
     * @return boolean
     */
    public static function subscribe($userId, $requestId) {

        if (Subscription::model()->exists('user_id=:user_id AND request_id=:request_id', array('user_id' => $userId, 'request_id' => $requestId))) {

            Subscription::model()->deleteAll('user_id=:user_id AND request_id=:request_id', array('user_id' => $userId, 'request_id' => $requestId));
            return array('subscribed' => false, 'count' => FeedAdmin::subscriberCount($requestId));
        }
        $subscription = new Subscription();
        $subscription->attributes = array(
            'user_id' => $userId,
            'request_id' => $requestId,
        );
        if ($subscription->validate() && $subscription->save()) {
            return array('subscribed' => true, 'subscriber_count' => FeedAdmin::subscriberCount($requestId));
        }
        return false;
    }

    /**
     * Данные профайла
     * @param int $userId
     * @return object
     */
    public static function profile($userId) {

        $user = User::model()->findByPk($userId);

        $criteria = new CDbCriteria();
        $criteria->condition = 't.user_id=' . $userId;
        $criteria->join = 'JOIN user_field ON t.field_id=user_field.id';
        $userData = UserData::model()->findAll($criteria);

        $result = (array) $user->attributes;
        foreach ($userData as $item) {

            $result[$item->field->name] = $item->value;
        }

        return (object) $result;
    }

    /**
     * Выборка данных юзера по заданным полям
     * @param UserFied $fields
     * @param int $userId
     * @return array
     */
    public static function getFields($fields, $userId) {

        $user = User::model()->findByPk($userId);
        $userfields = UserField::model()->findAll();
        $userfields = CHtml::listData($userfields, 'id', 'name');
        $attributes = User::model()->attributeNames();

        $result = array();
        foreach ($fields as $field) {

            if (in_array($field, $attributes)) {

                $result[$field] = $user->$field;
            } elseif (in_array($field, $userfields)) {

                $userData = UserData::model()->findByAttributes(array('user_id' => $userId, 'field_id' => array_search($field, $userfields)));
                $result[$field] = $userData ? $userData->value : '';
            }
        }

        return $result;
    }

    /**
     * Изменение дополнительных параметров юзера, хранящихся в таблице user_filed
     * @param int $userId
     * @param object $data
     * @return array
     */
    public static function editUserField($userId, $data) {

        $result = array();
        foreach ($data as $fieldName => $value) {

            $criteria = new CDbCriteria();
            $criteria->condition = 't.user_id=' . $userId;
            $criteria->join = ' JOIN user_field ON t.field_id=user_field.id AND user_field.name=\'' . $fieldName . '\'';

            $userData = UserData::model()->find($criteria);
            if ($userData) {
                $userData->value = $value;
                $userData->update();
            } else {
                $userField = UserField::model()->findByAttributes(array('name' => $fieldName));
                if ($userField) {
                    $userData = new UserData();
                    $userData->attributes = array(
                        'user_id' => $userId,
                        'field_id' => $userField->id,
                    );
                    $userData->save();
                }
            }
        }
        return $result;
    }

    /**
     * Изменение пароля
     * @param User $user
     * @param string $oldPassword
     * @param string $newPassword
     * @return boolean
     */
    public static function changePassword($user, $oldPassword, $newPassword) {

        if ($user->password == md5($oldPassword)) {

            $user->password = md5($newPassword);
            $user->update();
            $subject = 'Рупор: смена пароля';
            $message = 'Вы получили это письмо, потому что сменили пароль своей учетной записи. 
Ваши актуальные данные авторизации: 
логин: ' . $user->email . ';
пароль: ' . $newPassword . '.';
            $mail = new BRMail($user->email, $subject, $message);
            $mail->send();
            return true;
        }
        return false;
    }

    public static function uploadProfileImage($userId) {

        foreach ($_FILES as $name => $file)
            ;
        $file = CUploadedFile::getInstanceByName($name);
        $imageName = $userId . '.' . $file->extensionName;
        $file->saveAs(Yii::app()->getBasePath(true) . '/..' . Settings::PROFILE_IMAGE . $imageName);
        return Yii::app()->getBasePath(true) . '/..' . Settings::PROFILE_IMAGE . $imageName;
    }

    public static function changePasswordByKey($email){
        
        $user = User::model()->findByAttributes(array('email' => $email));
        if ($user){            
            $key = self::generatePassword($user);
            $link = Yii::app()->getBaseUrl(true) . Settings::CHANGE_PASSWORD_URL . '&link=' . $key;
            $subject = 'Рупор: смена пароля';
            $message = 'Вы получили это письмо, потому что подали заявку на смену пароля своей учетной записи. 
Пожалуйста, пройдите по ссылке: <a href="' . $link . '">' . $link . '</a> и укажите Ваш новый пароль.';
            $mail = new BRMail($user->email, $subject, $message);
            $mail->send();
            return true;
        }
        return false;
    }
}

?>
