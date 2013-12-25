<?php
	$this->pageTitle = $model->title;
?>
<div id="page" class="wrapper page-<?php echo $model->keyword; ?>">
	<h1><?php echo CHtml::encode($model->title); ?></h1>
	<?php echo $model->text; ?>
</div>