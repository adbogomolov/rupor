<?php
Yii::import('zii.widgets.jui.CJuiInputWidget');
/**
 * extended selectbox
 *
 * Its a wrapper of  http://harvesthq.github.com/chosen/
 *
 * @author Evgeny Bartashevich <earx@earx.spb.ru>
 * @link http://harvesthq.github.com/chosen/
 * @version 0.1
 *
 */
class ChosenWidget extends CJuiInputWidget {

	/**
	 * Publishes the required assets
	 */
	
	public $change;
	public $data;
	
	public function init()
	{
		return parent::init();
	}
	
	public function run() {
		list($name,$id)=$this->resolveNameId();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeDropDownList($this->model,$this->attribute, $this->data, $this->htmlOptions);
		else
			echo CHtml::dropDownList($name,$this->value, $this->data, $this->htmlOptions);
		
		$this->publishAssets($id);
	}

	/**
	 * Publises and registers the required CSS and Javascript
	 * @throws CHttpException if the assets folder was not found
	 */
	public function publishAssets($id) {

		$assets = dirname(__FILE__) . '/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);

		$options= CJavaScript::encode($this->options);
		
		if (is_dir($assets)) {
			
			Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerCssFile($baseUrl . '/css/chosen.css');

		} else {
			throw new CHttpException(500, 'ChosenWidget - Error: Couldn\'t find assets to publish.');
		}
		
		$script = "jQuery('#$id').chosen({$options})";
		if ($this->change)
			$script .= ".change({$this->change})"; //add onchange handler

		Yii::app()->clientScript->registerScript(__CLASS__."#$id", "$script;");
		
	}

}
?>
