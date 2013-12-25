<?php
/**
 * Настройки
 *
 * @author irina
 */
class Settings {
    
    const PHOTO = '/images/photo/';
    const DATA = '/data/';
	
    public static function getPath ($folder) {
        
        $folders = array(
            'photo' => self::PHOTO,
			'data' => self::DATA,
        );
        return Yii::app()->getBasePath(true) . '/..' . $folders[$folder];
    }
}

?>
