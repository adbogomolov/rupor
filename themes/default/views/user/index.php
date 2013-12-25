<?php
	$this->pageTitle = 'Ваш профиль';
?>
<div class="wrapper" id="profile">
<div class="columns">

	<div class="col col-ava">
		<?php echo $user->getAvatar(array('class'=>'ava ava100')); ?>
		<p class="center">
			<small><?php echo CHtml::link('Изменить фото' , array('settings')); ?></small>
		</p>
	</div>
	
	<div class="col col-info">
		<h2 class="username"><?php echo $user->getName(); ?></h2>
		<p class="date">Зарегистрирован: <?php echo date('d.m.Y', strtotime($user->created)); ?></p>
		<p><b>Место в рейтинге:</b> <b style="color:#e7642a"><?php echo $user->place; ?></b></p>
		
		<p>
			<b>Рейтинг:</b> <b style="color:#e7642a" rel="tooltip"><?php echo $user->rating; ?></b>
			<div style="display:none" style="font-size:1.3em">
				Добавление проблемы: +3
				<br/>
				Добавление комментария: +1
			</div>
		</p>
		
		<br/>
		
		<p><b>телефон:</b> <?php echo isset($user->eavAttributes['phone']) ? $user->eavAttributes['phone'] : ''; ?></p>
		<p><b>адрес:</b><?php echo isset($user->eavAttributes['address']) ? $user->eavAttributes['address'] : ''; ?></p>
		
		<br/>
		<br/>
		
		<h2>Мои бейджи</h2>
		<?php if (!empty($user->badges)): ?>
		<?php foreach($user->badges as $badge): ?>
		<div class="item">
		</div>
		<?php endforeach; ?>
		<?php else: ?>
		<div class="empty">Получайте награды за вашу активность</div>
		<?php endif; ?>
	</div>
	
	<div class="col col-history">
		<h2>Моя история</h2>
		<?php $this->widget('zii.widgets.CListView', array(
			'id'=>'UserFeed',
			'dataProvider'=>new CActiveDataProvider('Feed', array(
				'criteria' => array(
					'condition' => 'user_id = ' . $user->id,
					'order' => 'id DESC'
				),
				'pagination' => array(
					'pageSize' => 5
				),
			)),
			'itemView'=>'_item_feed',
			// 'enableHistory'=>true,
			'emptyText'=>'Здесь будет отображаться ваша активность.',
			'template'=>'{items} {pager}',
			'pager'=>array(
				'class'=>'CLinkPager',
				'prevPageLabel'=>'&larr;',
				'nextPageLabel'=>'&rarr;',
				'header'=>false,
			),
		)); ?>
	</div>
	
	<div class="col col-requests">
		<h2>Мои карточки проблем</h2>
		<?php
		
		$criteria = new CDbCriteria;
		$criteria->condition = 'author_id = ' .  $user->id;
		$criteria->order = 'id DESC';
		
		// Если привязана организация
		if ($user->officer_id)
		{
			$criteria->join = 'LEFT OUTER JOIN officer_request O ON O.request_id = t.id';
			$criteria->condition = 'O.officer_id = ' . $user->officer_id;
		}
		
		$this->widget('zii.widgets.CListView', array(
			'id'=>'userRequests',
			'dataProvider'=>new CActiveDataProvider('Request', array(
				'criteria' => $criteria,
				'pagination' => array(
					'pageSize' => 5
				),
			)),
			'itemView'=>'_item_request',
			'enableHistory'=>true,
			'ajaxUpdate'=>true,
			'emptyText'=>'Здесь будут ссылки на ваши проблемы.',
			// 'summaryText'=>"{start}&mdash;{end} из {count}",
			'template'=>'{items} {pager}',
			'pager'=>array(
				'class'=>'CLinkPager',
				'prevPageLabel'=>'&larr;',
				'nextPageLabel'=>'&rarr;',
				'header'=>false,
			),
		)); ?>
	</div>
	
</div>
</div>