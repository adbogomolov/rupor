<?php

$this->pageTitle = $model->title;

// Виджет комментариев
$commentsWidget = $this->createWidget('application.widgets.CommentsWidget', array(
	'request' => $model
));

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
	
	// event click to big image
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
			
			<!-- Description -->
			<div class="desc">
				<?php echo $model->description; ?>
				<div class="clear"></div>
			</div>
			<!-- Description END -->
			
		</div>
		<div class="angle"></div>
		</div>
		
	</div>
	<!-- Top Section END -->
	
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
JS
		); ?>
		<!-- Follow END -->
		
		<?php $commentsWidget->run(); ?>
		
		<p><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/ads.jpg" alt=""/></a></p>
		
		<?php if ($model->wikies): ?>
		<div class="block-gray">
			<h1>Полезная информация</h1>
			<?php foreach($model->wikies as $wiki): ?>
			<?php echo CHtml::link($wiki->name, array('/wiki/view', 'id'=>$wiki->id)); ?><br/>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
	</div>
	</div>
	<!-- Bottom Section END -->
</div>