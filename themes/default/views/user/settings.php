<?php
	$this->pageTitle = 'Настройки профиля';
?>
<div class="form wrapper">
	
	<h1><?php echo $this->pageTitle; ?></h1>
	
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'FormUser',
		'enableAjaxValidation' => true,
		'clientOptions'=> array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
		),
		'htmlOptions'=> array(
			'enctype' => 'multipart/form-data',
			'autocomplete' => 'off',
		),
	)); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'first_name'); ?>
		<?php echo $form->textField($model, 'first_name', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'first_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'last_name'); ?>
		<?php echo $form->textField($model, 'last_name', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'last_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'eavAttributes[phone]'); ?>
		<?php echo $form->textField($model, 'eavAttributes[phone]', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'eavAttributes[phone]'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'eavAttributes[address]'); ?>
		<?php echo $form->textField($model, 'eavAttributes[address]', array('class'=>'span3')) ?>
		<?php echo $form->error($model, 'eavAttributes[address]'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'avatar'); ?>
		<?php if ($model->avatar): ?>
		<div style="margin:0 0 10px">
			<span style="position:relative;display:inline-block">
				<a href="javascript:" title="Удалить" onclick="$('#User_avatar_hidden').val(0);$(this).parent().remove();" style="position:absolute;top:5px;right:5px;background-color:#fff" class="remove">x</a>
				<?php echo $model->getAvatar(); ?>
			</span>
		</div>
		<?php endif; ?>
		<?php echo $form->hiddenField($model, 'avatar', array('id'=>'User_avatar_hidden')); ?>
		<?php echo $form->fileField($model, 'avatar') ?>
		<?php echo $form->error($model, 'avatar'); ?>
	</div>
	
	<div class="row">
		<a href="javascript:" onclick="$('#User_passwords').toggle();">Изменить пароль</a>
	</div>
	
	<div id="User_passwords" style="display:<?php echo isset($model->errors['_password']) || isset($model->errors['_password2']) ? 'block' : 'none'; ?>">
		
		<div class="row">
			<?php echo $form->labelEx($model, '_password'); ?>
			<?php echo $form->passwordField($model, '_password', array('class'=>'span3', 'autocomplete'=>'off')) ?>
			<?php echo $form->error($model, '_password'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model, '_password2'); ?>
			<?php echo $form->passwordField($model, '_password2', array('class'=>'span3', 'autocomplete'=>'off')) ?>
			<?php echo $form->error($model, '_password2'); ?>
		</div>
		
	</div>
	
	<br/>
	
	<div class="row buttons">
		<input type="submit" value="Сохранить" class="btn btn-long"/>
	</div>

	<?php $this->endWidget(); ?>
</div>