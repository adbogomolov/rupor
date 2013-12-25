<?php if (Yii::app()->user->hasState('social')): ?>

	<div class="tooltip tooltip-orange" style="position:relative">
	<h3><?php
		$social = Yii::app()->user->getState('social');
		if (!empty($social['photo']))
			echo CHtml::image($social['photo'], '', array('style'=>'vertical-align:middle;margin:0 10px 0 0', 'width'=>30));
		if (!empty($social['name']['full_name']))
			$name = $social['name']['full_name'];
		elseif (!empty($social['name']['first_name']) || !empty($social['name']['last_name']))
			$name = trim($social['name']['first_name'] .' '. $social['name']['last_name']);
		elseif (!empty($social['nickname']))
			$name = $social['nickname'];
		else
			$name = $social['uid'];
		echo CHtml::link($name, $social['identity']);
	?></h3>
	Профиль не связан с какой-либо учетной записью.
	Войдите на сайт или зарегистирируйтесь, чтобы связать свою учетную запись с профилем социальной сети.
	Или выберите другую социальную сеть для входа.
	</div>
	<br/>
	
<?php endif; ?>
<?php if (Yii::app()->user->hasState('officer')): ?>

	<?php
		$state = Yii::app()->user->getState('officer');
		$officer = Officer::model()->findByPk($state['id']);
	?>
	<?php if ($officer): ?>
	<div class="tooltip tooltip-orange" style="position:relative">
	<h3><?php echo $officer->post; ?> (<?php echo $officer->address; ?>)</h3>
		Организация не связана с какой-либо учетной записью.
		Войдите на сайт или зарегистирируйтесь, чтобы связать свою учетную запись с организацией.
	</div>
	<br/>
	<?php endif; ?>
	
<?php endif; ?>