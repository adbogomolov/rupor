<?php
	$this->pageTitle = 'Восстановление пароля';
	$this->breadcrumbs[] = $this->pageTitle;
?>

<div class="form wrapper">
	
	<h1>Восстановление пароля</h1>
	
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'FormRecovery',
		'enableAjaxValidation' => true,
		'clientOptions'=> array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
		)
	)); ?>
	
	<?php if ($model->scenario == 'complete'): ?>
	
		<div class="row">
			<?php echo $form->labelEx($model, 'password'); ?>
			<?php echo $form->passwordField($model, 'password', array('class'=>'span3')) ?>
			<?php echo $form->error($model, 'password'); ?>
			<p class="hint">Укажите свой новый пароль!</p>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model, 'password2'); ?>
			<?php echo $form->passwordField($model, 'password2', array('class'=>'span3')) ?>
			<?php echo $form->error($model, 'password2'); ?>
		</div>
		
	<?php else: ?>
		
		<div class="row">
			<?php echo $form->labelEx($model, 'email'); ?>
			<?php echo $form->textField($model, 'email', array('class'=>'span3')) ?>
			<?php echo $form->error($model, 'email'); ?>
			<p class="hint">
				Адрес электронной почты, указанный в Вашей учетной записи.
				<br/>
				На него будет отправлено письмо с инструкцией по восстановлению доступа.
			</p>
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
	
	<?php endif; ?>
		
	
	<br/>
	
	<div class="row buttons">
		<input type="submit" value="Восстановить пароль" class="btn"/>
	</div>
	
	<?php $this->endWidget(); ?>
</div>