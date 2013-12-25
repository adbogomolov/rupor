<?php

$this->pageTitle = $model->title;

// Виджет комментариев
$commentsWidget = $this->createWidget('application.widgets.CommentsWidget', array(
	'request' => $model
));

$linkOptions = array();
$urlBase = '';
if($this->layout == '//layouts/iframe')
{
	$linkOptions['target'] = '_blank';	
	$urlBase = 'http://e-rupor.ru';
}

// TODO: вынести все в rupor.map.js
// register client scripts
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl .'/js/jquery.ad-gallery.js');
$cs->registerScriptFile('http://api-maps.yandex.ru/2.0/index.xml?load=package.map,package.controls,package.geocode,package.geoObjects&lang=ru-RU');
$cs->registerScript(__FILE__, <<<JS
	ymaps.ready(function(){
		var coords = [{$model->lat}, {$model->lng}];
		var map = new ymaps.Map($('#request_map')[0], {
			zoom: 15,
			center: coords,
			behaviors: ['default', 'scrollZoom']
		});
		map.events.add('boundschange', function (e) {
			map.setCenter(coords);
		});
		var placemark = new ymaps.Placemark(coords, {}, {
			preset: Rupor.request.getIcon({$model->status})
		});
		map.geoObjects.add(placemark);
		Rupor.request.map = map;
		Rupor.request.placemark = placemark;
	});
	
	// initialize gallery
	$('.ad-gallery').adGallery({
		display_next_and_prev: false,
		update_window_hash: false,
		effect: 'fade'
	});
	// click to big image
	$('.ad-gallery').on('click', '.ad-image', function(){
		var wrap = $(this).parent().parent();
		var image_url = $(this).find('img').attr('src');
		var link = wrap.find('a[href="'+image_url+'"]');
		// youtube video
		if (link.data('video-id')){
			$.fancybox({
				width: '50%',
				height: '60%',
				autoScale: false,
				transitionIn: false,
				transitionOut: false,
				type: 'iframe',
				padding: 0,
				href: 'http://www.youtube.com/embed/' + link.data('video-id')
			});
		}else{
			$.fancybox.open({href: image_url, padding: 0});
		}
	});
JS
);

?>
<div class="clear"></div>
<div id="request">
	
	<!-- Top Section -->
	<div id="request_top">
		<div class="wrapper">
			<?php
				// Изменение статуса проблемы
			?>
			<?php if (Yii::app()->user->checkAccess('changestatus')): ?>
			<div class="f-right">
				<?php echo CHtml::dropdownList('', $model->status, Request::statusList(), array(
					'rel'=>'tooltip',
					'id'=>'request_status',
					'data-tooltip-options'=> CJSON::encode(array('tipClass'=>'tooltip tooltip-orange')),
					'options' => array(
						Request::STATUS_INPROGRESS=>array('disabled'=>!Yii::app()->user->checkAccess('changestatus', array(
							'status'=>Request::STATUS_INPROGRESS
						))), // Только организации могут поставить статус "в работе"
						Request::STATUS_UNSOLVED=>array('disabled'=>!Yii::app()->user->checkAccess('changestatus', array(
							'status'=>Request::STATUS_UNSOLVED
						))), // Только пользователи могут поставить статус "не решена"
					),
					'title'=>'Изменить статус проблемы',
				)); ?>
			</div>
			<?php
			// JS для изменения статуса
			$cs->registerScript(__FILE__ . '#changestatus', <<<JS
			// change request status
			$('#request_status').change(function(){
				var status = parseInt($(this).val(), 10);
				$.ajax({
					url: '/request/changestatus',
					data: {id: "{$model->id}", status: status},
					dataType: 'json',
					success: function(res){
						if (res.status==1){
							$.fn.yiiListView.update('RequestFeed');
							if (Rupor.request.placemark){
								Rupor.request.placemark.options.set('preset', Rupor.request.getIcon(status));
							}
						}
						Rupor.box.notice(res.message);
					}
				});
			});
JS
); ?>
			<?php endif; ?>
			<?php /*
				<a href="#">Лента сообщений</a>
				<a href="#">Городское хозяйство</a>
				<a href="#">Новостройки</a>
			*/ ?>
			<h1><?php echo $model->title; ?></h4>
			<?php // <h4 class="subtitle">Заголовок длинный и очень длинный</h4> ?>
		</div>
		
		<div class="block-orange">
		<div class="wrapper">
			
			<!-- Media Gallery -->
			<div class="gallery">
				<div class="ad-gallery">
				<?php if ($model->files||$model->videos): ?>
					<div class="ad-image-wrapper"></div>
					<div class="ad-controls"></div>
					<div class="ad-nav">
					<div class="ad-thumbs">
						<ul class="ad-thumb-list">
						<?php foreach($model->files as $file): // Фотографии ?>
						<li class="ad-thumb">
							<a href="<?php echo $file->baseUrl; ?>">
								<img src="<?php echo $file->thumbUrl; ?>" alt=""/>
								<span></span>
							</a>
						</li>
						<?php endforeach; ?>
						<?php foreach($model->videos as $video): // Видео YouTube ?>
						<li class="ad-thumb-video">
							<a href="<?php echo $video->baseUrl; ?>" data-video-id="<?php echo $video->id; ?>">
								<img src="<?php echo $video->thumbUrl; ?>" alt=""/>
								<span></span>
							</a>
						</li>
						<?php endforeach; ?>
						</ul>
					</div>
					</div>
				<?php else: ?>
					<div class="empty">Нет фото</div>
				<?php endif; ?>
				</div>
			</div>
			<!-- Media Gallery END -->
			
			<!-- Location END -->
			<div class="map" id="request_map"></div>
			<!-- Location END -->
			
			<div class="clear"></div>
			
			<!-- Information -->
			<ul class="row first">
				<li class="col1">
					<?php if ($model->author): ?>
					<?php echo $model->author->getAvatar(array('class'=>'ava ava27')); ?>
					<b>Проблему отправил(а)</b>
					<?php echo $model->author->getName(); ?>
					<?php else: ?>
					<b>Проблему отправил</b>
					Аноним
					<?php endif; ?>
				</li>
				<li class="col2"><b>Местоположение проблемы</b><?php echo CHtml::encode($model->address) ?></li>
				<li class="col3"><b>Добавлено</b><?php echo CDateTime::format($model->created); ?></li>
			</ul>
			<!-- Information END -->
			
			<?php $this->widget('EListView', array(
				'id'=>'RequestFeed',
				'dataProvider'=>new CActiveDataProvider('Feed', array(
					'criteria' => array(
						'condition' => 'request_id = '. $model->id .' AND event IN('. implode(',', array(
							Feed::EVENT_CHANGE_STATUS,
							Feed::EVENT_ADD_STATEMENT,
						)) .')',
						'order' => 'id DESC'
					),
					'pagination' => array(
						'pageSize' => 3
					),
				)),
				'itemView'=>'_item_events',
				// 'enableHistory'=>true,
				'emptyText'=>'',
				'template'=>'{items} {pager}',
			)); ?>
			
			<!-- Description -->
			<div class="desc">
				<?php echo CHtml::encode($model->description); ?>
				<div class="clear"></div>
			</div>
			<!-- Description END -->
			
		</div>
		<div class="angle"></div>
		</div>
		
	</div>
	<!-- Top Section END -->
	
	<!-- Middle Section -->
	<div id="request_middle">
		
		<div class="wrapper">
			
			<div id="request_instances">
			<div class="block">
				
				<?php if ($officers): // Если доступен список ответственных ?>
					
					<h2 style="margin-top:-25px">Проблема отправлена в:</h2>
					<ul>
						<?php foreach($officers as $officer): ?>
						<?php if ($officer->choosen): ?>
						<li>
							<div class="name"><?php echo $officer->fullname; ?></div>
							<?php echo $officer->post; ?>
						</li>
						<?php $has_choosen = true; ?>
						<?php else: ?>
						<?php $has_not_choosen = true; ?>
						<?php endif; ?>
						<?php endforeach; ?>
						<?php if (empty($has_choosen)): ?>
						<div class="empty">Еще не отправлено</div>
						<?php endif; ?>
					</ul>
					
					<?php if (!empty($has_not_choosen)): ?>
					<h2>Еще можно отправить в:</h2>
					<ul>
						<?php
							$time = time(); // текущее время
							$created_time = strtotime($model->created); // время в формате UNIX Timestamp
						?>
						<?php foreach($officers as &$officer): ?>
						<?php if (!$officer->choosen): ?>
						<?php
							$create_reqeust = true; // Отобразить форму добавления заявки
							$available_time = $created_time + (60 * 60 * 24 * (int)$officer->days_count);
							
							$officer->disabled = $available_time > $time;
						?>
						<li<?php echo $officer->disabled ? ' class="disabled"' : ''; ?>>
							<div class="name"><?php echo $officer->fullname; ?></div>
							<p><?php echo $officer->post; ?></p>
							<?php if ($officer->disabled): ?>
							<em>(Доступно через: <?php echo CDateTime::timeDiff($available_time); ?>)</em>
							<?php endif; unset($officer); ?>
						</li>
						<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					
					<?php if (!empty($create_reqeust)): ?>
					<a href="javascript:" class="f-right btn btn-orange" onclick="$('#request_statement').slideToggle();$('html, body').animate({scrollTop: $(this).offset().top - 13}, 500 );">Создать заявление</a>
					<b>Выберите получателей заявления</b>
					<?php endif;?>
					
				<?php else: ?>
				
					<p class="center">Для вашего местоположения недоступно ни одной инстанции, пожалуйста обратитесь к администратору.</p>
				
				<?php endif; ?>
			
				<div class="clear"></div>
				<div class="angle"></div>
				<div class="angle top"></div>
			</div>
			</div>
			
			<?php if (!empty($create_reqeust)): // Если доступно создание заявления ?>
			
			<div id="request_statement" <?php echo empty($statement->errors) ? ' style="display:none"' : ''; ?>>
			<div class="wrap">
				<div class="form">
				
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id' => 'FormRequestStatement',
					'htmlOptions'=> array(
						'autocomplete'=>'off'
					),
					'enableAjaxValidation' => true,
					'clientOptions'=> array(
						'validateOnSubmit' => true,
						'validateOnChange' => false,
						'afterValidate'=>"js:function(form, data, hasError){
							if (!hasError){
								Rupor.box.notice('Заявление успешно отправлено!');
								$('html, body').animate({scrollTop:0}, 300);
								setTimeout(function(){location.reload(1)}, 1500);
							}else{
								$('html, body').animate({scrollTop: form.offset().top - 20}, 500 );
							}
							return false;
						}"
					),
				)); ?>
					
					<div class="row first">
						
						<?php $this->renderPartial('_legal'); ?>
						
						<div class="f-left">
							<?php 
								$officers_list = array();
								$options = array();
								foreach($officers as $officer)
								{
									if (!$officer->choosen)
									{
										$officers_list[$officer->id] = $officer->post;
										if ($officer->disabled)
										{
											$cs->registerScript(__FILE__ . '#officers#' . $officer->id, '$(\'input[value="'. $officer->id .'"][name="FormRequestStatement\[officers\]\[\]"]\').attr("disabled", true).parent().addClass("disabled");');
										}
									}
								}
							?>
							<?php echo $form->checkBoxList($statement, 'officers', $officers_list, array(
								'template' => '<div>{input} {label}</div>',
								'separator' => '',
							));
							$cs->registerScript(__FILE__ . '#officers', '$(\'input[name="FormRequestStatement\[officers\]\[\]"]:not(:disabled)\').attr("checked", 1);');
							?>
							<?php echo $form->error($statement, 'officers'); ?>
						</div>
						<div class="f-right">
							<div>
							<?php echo $form->textField($statement, 'name', array('placeholder'=>'Ваши ФИО')); ?>
							<?php echo $form->error($statement, 'name'); ?>
							</div>
							<div>
							<?php echo $form->textField($statement, 'email', array('placeholder'=>'Эл. почта')); ?>
							<?php echo $form->error($statement, 'email'); ?>
							</div>
							<div>
							<?php echo $form->textField($statement, 'address', array('placeholder'=>'Почтовый адрес')); ?>
							<?php echo $form->error($statement, 'address'); ?>
							</div>
							<div>
							<?php echo $form->textField($statement, 'phone', array('placeholder'=>'Телефон')); ?>
							<?php echo $form->error($statement, 'phone'); ?>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					
					<div class="row message">
						<h2>Обращение</h2>
						
						<?php echo $form->textArea($statement, 'body', array('style' => 'width:98%;height:150px')); ?>
						<?php echo $form->error($statement, 'body'); ?>
						
						<br/>
						<br/>
						
						<?php foreach($model->tagRequests as $obj): // Выборка первого шаблона из доступных тем ?>
							<?php if ($obj->tag && !empty($obj->tag->statement)): ?>
							<?php echo $form->hiddenField($statement, 'tag_id', array('value'=>$obj->tag->primaryKey)); ?>
							<?php echo nl2br($obj->tag->statement); ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					
					<div class="row data">
						<p>
							<?php if ($user): ?><span class="f-right name"><?php echo $user->getName(); ?></span><?php endif; ?>
							<?php echo date('d.m.Y'); ?>
						</p>
						<p>
							Обращение составлено при помощи сервиса Рупор (www.e-rupor.ru)<br/>
							
								<?php 
								$url = $this->createAbsoluteUrl($model->getHref());
								$url = str_replace('widget.', '', $url);
							?>
							Ссылка на сообщение: <?php echo CHtml::link($url, $url); ?>
						</p>
						<div class="address">Адрес: <?php echo $model->address; ?></div>
						<div class="image">
							<?php foreach($model->files as $file): ?>
							<?php echo $file->toString(); ?>
							<?php endforeach; ?>
						</div>
					</div>
					
					<div class="row submit">
						<input type="submit" class="btn btn-big btn-black" value="Отправить"/>
					</div>
				
				<?php $this->endWidget(); ?>
				</div>
				<a href="javascript:" onclick="$('#request_statement').slideUp();return false;" class="close"></a>
			</div>
			</div>
			<?php endif;?>
			
		</div>
		
	</div>
	<!-- Middle Section END -->
	
	<!-- Bottom Section -->
	<div id="request_bottom">
	<div class="wrapper">
		
		<!-- Social -->
		<div class="likes">
			<div>
				<?php echo $this->renderPartial('//partials/share'); ?>
			</div>
		</div>
		<!-- Social END -->
		
		<!-- Follow -->
		<div class="follow">
			<a href="javascript:" id="request_like" class="btn-like btn-big">+<span><?php echo $model->likes_count; ?></span></a>
			<p>
				<a href="javascript:" id="request_subscribe"><?php if ($model->subscribed()): ?>Отписатся<?php else: ?>Подписатся<?php endif; ?></a>
			</p>
		</div>
		<?php
		$cs->registerScript(__FILE__ . '#like_subscribe', <<<JS
		$('#request_like').click(function(){
			var that = $(this);
			Rupor.request.like('{$model->id}', function(res){
				if (res.status == 1) that.find('span').text(res.count);
			});
		});
		$('#request_subscribe').click(function(){
			var that = $(this);
			Rupor.request.subscribe('{$model->id}', function(res){
				res.status == 1 ? that.text('Отписаться') : that.text('Подписатся');
			});
		});
JS
		); ?>
		<!-- Follow END -->
		
		<?php $commentsWidget->run(); ?>
		
		<p><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ads.jpg" alt=""/></a></p>
		
		<?php if ($model->wikies): ?>
		<div class="block-gray">
			<h1>Полезная информация по проблеме</h1>
			<?php foreach($model->wikies as $wiki): ?>
			<?php echo CHtml::link($wiki->name, array('/wiki/view', 'id'=>$wiki->id)); ?><br/>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
	</div>
	</div>
	<!-- Bottom Section END -->
</div>