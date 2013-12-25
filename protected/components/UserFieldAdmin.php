<?php
/**
 * Администрирование полей юзера, находящихся в таблице user_filed
 *
 * @author irina
 */
class UserFieldAdmin {
		 
	const TEXT = 0;
	const MULTISELECT = 1;
	const DROPDOWN = 2;
	const RADIO = 3;
	const CHECKBOX = 4;
	
	const BR_INT = 0;
	const BR_VARCHAR = 1;
	const BR_FLOAT = 2;
	const BR_TEXT = 3;
	const BR_TIMESTAMP = 4;
	/**
	 * Список типов данных
	 * @param int $type
	 * @return string/array of string
	 */
	public static function getDataType($type) {
		
		return array(
			self::INT => 'int',
			self::VARCHAR => 'varchar',
			self::FLOAT => 'float',
			self::TEXT => 'text',
			self::TIMESTAMP => 'timestamp',
		);
	}
	/**
	 * Список типов данных mysql
	 * @param int $type
	 * @return string/array of string
	 */
	public static function getInputType($type = null) {
		
		$types = array(
			self::TEXT => 'text',
			self::MULTISELECT => 'multiselect',
			self::DROPDOWN => 'dropdown',
			self::RADIO => 'radio',
			self::CHECKBOX => 'checkbox',
		);
		if (isset($types[$type])) {
			return $types[$type];
		}
		return $types;
	}
	/**
	 * Добавление поля
	 * @param string $name
	 * @param string $type
	 * @param boolean $required
	 * @param object $data
	 * @return boolean
	 */
	public static function addField ($name, $type, $required, $data = null) {
		
		$field = new UserField();
		$field->attributes = array(
			'name' => $name,
			'input_type' => $type,
			'rquired' => $required,
			'data' => $data,
		);
		if ($field->validate() && $field->save()) {
			
			return true;
		}
		return false;
	}
	/**
	 * Изменение поля
	 * @param int $fieldId
	 * @param object $data
	 * @return boolean
	 */
	public static function editField ($fieldId, $data) {
		
		$field = UserField::model()->findByPk($fieldId);
		if (!$field) {
			return false;
		}
		foreach ($data as $attribute => $value) {
			
			$field->$attribute = $value;			
		}
		if ($field->validate() && $field->update()) {
			return true;
		}
		return false;
	}
	/**
	 * Удаление поля
	 * @param int $fieldId
	 * @return UserField
	 */
	public static function removeField ($fieldId) {
		
		return UserField::model()->deleteByPk($fieldId);
	}
	/**
	 * Список полей
	 * @return array
	 */
	public static function fieldList () {
		
		$fields = UserField::model()->findAll();
		$result = array();
		foreach ($fields as $field) {
			
			$item = $field->attributes;
			$item['input_type'] = self::getInputType($field->input_type);
			$result[] = $item;
		}
		return $result;
	}
}

?>
