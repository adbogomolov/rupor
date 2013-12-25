<?php
	$this->pageTitle = Yii::t('user', 'Авторизация');
?>

<div class="wrapper">

<?php $this->renderPartial('_states'); ?>
	
<div class="form" style="float:left;width:320px">
	<h1>Вход</h1>
	
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'FormLogin',
		'enableAjaxValidation' => false,
		'clientOptions'=> array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
		)
	)); ?>
	
	<div class="row">
		<?php echo $form->label($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('class'=>'span3')); ?>
		<?php echo $form->error($model, 'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row">
		<label for="FormLogin_remember">
			<?php echo $form->checkBox($model, 'remember'); ?>
			&nbsp;<span>Запомнить меня</span>
		</label>
	</div>
	
	<br/>
	<div class="row buttons">
		<input type="submit" value="Войти" class="btn btn-long"/>
		&nbsp;
		<?php echo CHtml::link('Забыли пароль?', array('/user/recovery')) ?>
	</div>
	
	<?php $this->endWidget(); ?>
	
</div>
<div style="float:left;margin:0 0 0 50px">
	<h1>Вход через социальные сети</h1>
	<?php Yii::app()->loginza->render('Войти через социальные сети'); ?>
</div>
</div>