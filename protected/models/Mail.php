<?php

class Mail extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{mail}}';
	}

	public function rules()
	{
		return array(
			array('id, subject, body', 'required'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'subject' => 'Тема',
			'body' => 'Сообщение',
		);
	}

	/**
	 * Отправка сообщения на почту
	 * @return bool
	 */
	public static function send($id, $to, $vars=array(), $headers=array())
	{
		$mail = self::model()->findByPk($id);
		if (!$mail) return;
		
		$from = 'noreply@'.$_SERVER['HTTP_HOST']; // TODO: from YII config
		
		$headers = array_merge(array(
			'MIME-Version' => '1.0',
			'Content-type' => 'text/html; charset=UTF-8',
			'From' => '=?UTF-8?B?'. base64_encode(Yii::app()->name) .'?= <'.$from.'>',
			'Content-Transfer-Encoding' => 'base64',
			'Precedence' => 'bulk',
		), $headers);
		
		$mail->subject = self::prepare($mail->subject, $vars);
		$mail->body = self::prepare($mail->body, $vars);
		
		$heades_raw = '';
		foreach($headers as $key => $value)
			$heades_raw .= "{$key}: {$value}\n";
		
		return @mail($to, '=?UTF-8?B?' . base64_encode($mail->subject) . '?=', base64_encode($mail->body), trim($heades_raw), '-f'.$from);
	}
	
	/**
	 * Замена переменных
	 * @return void
	 */
	private static function prepare($body, $vars=array())
	{
		$vars['sitename'] = Yii::app()->name;
		$vars['siteurl'] = Yii::app()->request->getbaseUrl(true);
		$vars['date'] = date('d.m.Y');
		$vars['time'] = date('H:i');
		
		foreach($vars as $key => $value)
		{
			$body = str_replace('{%'.$key.'%}', $value, $body);
		}
		
		return $body;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}