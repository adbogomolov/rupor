<?php

require_once 'BaseApiController.php';

class UserController extends BaseApiController {

    public function actionSignUp() {

        $this->_checkRequest($this->request, array('user', 'user' => array('email', 'password')));
        $result = UserAdmin::signUp($this->request->user);
        if (!$result->error) {
            $token = BRToken::generateToken($result->user->id);
            Api::sendResponse(Api::OK, array('token' => $token));
        } else {
            Api::sendResponse(Api::INVALID, array('res' => $result));
        }
    }

    public function actionSignIn() {

        $this->_checkRequest($this->request, array('email', 'password'));
        $user = UserAdmin::authorization($this->request->email, $this->request->password);
        if ($user) {
            $token = BRToken::generateToken($user->id);
            Api::sendResponse(Api::OK, array('token' => $token));
        }
        Api::sendResponse(Api::INVALID);
    }

    public function actionSocialAccount() {

        $this->_checkRequest($this->request, array('net_type'));        
        $data = isset($this->request->oauth) ? $this->request->oauth : null;
        
        $result = UserAdmin::signInBySocialNet($this->request->net_type, $this->request->id, $data);
        Api::sendResponse(Api::OK, array('res' => $result));
        if (!$result) {
            Api::sendResponse(Api::ACCOUNT_NOT_FOUND);
        }
        $userId = $result->user_id;
        $token = BRToken::generateToken($userId);

        Api::sendResponse(Api::OK, array('token' => $token, 'signup' => $result->signup));
    }

    public function actionProfile() {

        $this->_checkRequest($this->request, array('token'));

        $user = $this->_authorize($this->request->token);
        $profile = UserAdmin::profile($user->id);

        Api::sendResponse(Api::OK, array('user' => $profile));
    }

    public function actionEditProfile() {

        $this->_checkRequest($this->request, array('token', 'user'));
        $user = $this->_authorize($this->request->token);
        if (isset($this->request->user->email) && ($this->request->user->email == '') && $user->email && $user->email != '') {

            Api::sendResponse(Api::INVALID);
        }
        $editRequest = UserAdmin::editProfile($user, $this->request->user);
        if ($editRequest->error) {
            Api::sendResponse(Api::INVALID);
        }
        Api::sendResponse(Api::OK);
    }

    public function actionSendNewPassword() {

        $this->_checkRequest($this->request, array('email'));
        $user = User::model()->findByAttributes(array('email' => $this->request->email));
        if (!$user) {
            Api::sendResponse(Api::ACCOUNT_NOT_FOUND);
        }
        UserAdmin::saveTempPassword($user);

        Api::sendResponse(Api::OK);
    }

    public function actionRecoverPassword() {

        $this->_checkRequest($this->request, array('key', 'new_password'));

        $user = UserAdmin::recoverPassword($this->request->key, $this->request->new_password);
        if (!$user) {
            Api::sendResponse(Api::ACCOUNT_NOT_FOUND);
        }
        $token = BRToken::generateToken($user->id);
        Api::sendResponse(Api::OK, array('token' => $token));
    }

    public function actionSubscriptionFeed() {

        $this->_checkRequest($this->request, array('token'));
        $user = $this->_authorize($this->request->token);
        $firstId = isset($this->request->first_id) ? $this->request->first_id : null;
        Api::sendResponse(Api::OK, array('feed' => FeedAdmin::subscriptionFeed($user->id, $firstId)));
    }

    public function actionSubscribe() {

        $this->_checkRequest($this->request, array('token', 'request_id'));
        $user = $this->_authorize($this->request->token);
        $result = UserAdmin::subscribe($user->id, $this->request->request_id);
        if ($result === false) {
            Api::sendResponse(Api::INVALID);
        }
        Api::sendResponse(Api::OK, $result);
    }

    public function actionMyRequests() {

        $this->_checkRequest($this->request, array('token'));
        $user = $this->_authorize($this->request->token);
        $firstId = isset($this->request->first_id) ? $this->request->first_id : null;
        $requests = RequestAdmin::getByUser($user->id, $firstId);

        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests)));
    }

    public function actionChangePassword() {

        $this->_checkRequest($this->request, array('token', 'old_password', 'new_password'));
        $user = $this->_authorize($this->request->token);
        if (UserAdmin::changePassword($user, $this->request->old_password, $this->request->new_password)) {

            Api::sendResponse(Api::OK);
        }
        Api::sendResponse(Api::BAD_REQUEST);
    }
}
