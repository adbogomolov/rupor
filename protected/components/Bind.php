<?php
/**
 * Добавление перекрестных данных
 *
 * @author irina
 */
class Bind {
	
	public $model;
	public $firstName;
	public $secondName;
	/**
	 * Конструктор
	 * @param string $model
	 * @param string $firstAttribute
	 * @param string $secondAttribute
	 */
	public function __construct($model, $firstAttribute, $secondAttribute) {
		
		$this->model = $model;
		$this->firstName = $firstAttribute;
		$this->secondName = $secondAttribute;
	}
	/**
	 * Создание объекта модели
	 * @param value type $firstValue
	 * @param value type $secondValue
	 * @return boolean
	 */
	public function create($firstValue, $secondValue) {
		
		$modelName = $this->model;
		$object = new $modelName();
		$object->attributes = array(
			$this->firstName => $firstValue,
			$this->secondName => $secondValue,
		);
		if ($object->validate() && $object->save()) {
			
			return true;
		}
		return false;
	}
}

?>
