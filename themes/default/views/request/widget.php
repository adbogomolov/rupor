<?php
$this->pageTitle = '';
$this->layout = '//layouts/iframe';
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/rupor.map.js');
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/rupor.main.js');
$cs->registerScript(__FILE__, <<<JS
	Rupor.main.init();
JS
);
?>
<div id="layout">
	<div id="leftPanel"><!-- Feed here! --></div>
	<div id="splitter"><span></span></div>
	<div id="rightPanel">
		<div id="map"><!-- Map here! --></div>
		<div id="map-loader"></div>
		<form id="filter">
			<div class="add">
				<?php echo CHtml::link('Добавить проблему', array('/request/add')); ?>
			</div>
		</form>
	</div>
</div>