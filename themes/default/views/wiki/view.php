<?php
	$this->pageTitle = $model->name;
?>
<div class="wrapper">
	<h1><?php echo CHtml::encode($model->name); ?></h1>
	<?php echo nl2br($model->description); ?>
</div>