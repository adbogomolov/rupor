<?php
	$this->pageTitle = 'Регистрация';
?>

<div class="form wrapper">
	
	<?php $this->renderPartial('_states'); ?>
		
	<h1>Регистрация</h1>
	<p>После регистрации на сервисе вы получите возможность отправлять заявления обслуживающим службам и принимать участие в обсуждении и решении проблем.</p>
	
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'FormRegistration',
		'enableAjaxValidation' => true,
		'clientOptions'=> array(
			'validateOnSubmit'=>true,
			'validateOnChange'=>false,
		)
	)); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'first_name'); ?>
		<?php echo $form->textField($model, 'first_name', array('class'=>'span4')) ?>
		<?php echo $form->error($model, 'first_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'last_name'); ?>
		<?php echo $form->textField($model, 'last_name', array('class'=>'span4')) ?>
		<?php echo $form->error($model, 'last_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('class'=>'span4', 'autocomplete'=>'off')) ?>
		<?php echo $form->error($model, 'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'password'); ?>
		<?php echo $form->passwordField($model, 'password', array('class'=>'span4', 'autocomplete'=>'off')) ?>
		<?php echo $form->error($model, 'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'password2'); ?>
		<?php echo $form->passwordField($model, 'password2', array('class'=>'span4')) ?>
		<?php echo $form->error($model, 'password2'); ?>
	</div>
	
	<?php if ($model->showCaptcha()): ?>
		<div class="row captcha">
			<?php $this->widget('CCaptcha', array('showRefreshButton' => true)); ?>
			<?php echo $form->labelEx($model, 'verifyCode'); ?>
			<?php echo $form->textField($model, 'verifyCode'); ?>
			<?php echo $form->error($model, 'verifyCode'); ?>
			<div class="hint">Введите код указанный на картинке</div>
		</div>
	<?php endif; ?>
	
	<div class="row">
		<label for="FormRegistration_rules">
			<?php echo $form->checkBox($model, 'rules'); ?>
			Я принимаю условия <?php echo CHtml::link('пользовательского соглашения', array('/page/view', 'url'=>'agreement'), array('target'=>'_blank', 'rel'=>'modal')); ?>
		</label>
		<?php echo $form->error($model, 'rules'); ?>
	</div>
	
	<br/>
	
	<div class="row buttons">
		<input type="submit" value="Зарегистрироваться" class="btn btn-big btn-black"/>
	</div>

	<?php $this->endWidget(); ?>
</div>