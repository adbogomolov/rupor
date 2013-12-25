<?php
	$this->pageTitle = 'Рупор';
	$this->layout='//layouts/main';
	
	// Отображать выбор местоположения
	$this->setPageState('location', true);
	
	$cs = Yii::app()->clientScript;
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/rupor.map.js');
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/rupor.main.js');
	$cs->registerScript(__FILE__, <<<JS
	Rupor.main.init();
	$('.widget-modal').click(function(){
		var params = $.parseJSON($.cookie(Rupor.cookieName));
		if (params['my']){
			delete params['my'];
			params['uid'] = Rupor.uid;
		}
		var widgetUrl = 'http://widget.e-rupor.ru/request/widget?'+$.param(params);
		var widgetCode = '<iframe width="100%" height="500" src="'+ widgetUrl +'"></iframe>';
		$.fancybox({
			centerOnScroll: true,
			padding: 30,
			minHeight: 0,
			content: 'Код для вставки (<a href="/faq">подробнее</a>):<br/><textarea style="width:500px;height:150px" onclick="this.select();">'+ widgetCode +'</textarea>'
		});
		return false;
	});
JS
);
?>

<?php /* if ($layers): ?>
<!-- Layers -->
<div id="layers">
	<a><b><span>Слои</span></b></a>
	<ul>
		<?php foreach($layers as $l): ?>
		<li data-layer="<?php echo $l->id; ?>">
			<?php echo CHtml::link($l->name, array('/site/index', 'layer'=>$l->id)); ?>
		</li>
		<?php endforeach ?>
	</ul>
</div>
<!-- Layers END -->
<?php endif; */ ?>

<!-- Tags -->
<div id="tags">
	<div class="wrapper">
		<ol>
			<?php $i=-1; foreach(Tag::treeArray() as $tag): $i++; ?><li class="i<?php echo ($i%4)+1; ?>">
				<h2 data-tag="<?php echo $tag['id']; ?>"><?php echo $tag['name']; ?></h2>
				<div>
					<ul>
						<?php if (!empty($tag['children'])): $size = sizeof($tag['children']); ?>
						<?php $k=-1; foreach($tag['children'] as $t): $k++; ?>
						<?php if ($k==ceil($size/2)&&$size-$k>0): ?>
						</ul><ul>
						<?php endif; ?>
						<li><a href="javascript:" data-tag="<?php echo $t['id']; ?>"><span><?php echo CHtml::encode($t['name']); ?></span></a></li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
			</li><?php endforeach; ?>
		</ol>
		<div class="actions">
			<!--
			<span class="select">
				Выделить:
				<a href="#">все</a>
				<a href="#">сбросить</a>
			</span>
			-->
			<span class="total">Выбрано: <span>0</span></span>
			<a href="#" class="toggle"><span>Список тем</span></a>
		</div>
	</div>
	<div class="angle"></div>
	<div class="angle top"></div>
</div>
<!-- Tags END -->

<!-- Layout -->
<div id="layout">
	
	<div id="leftPanel"><!-- Feed here! --></div>
	
	<div id="splitter"><span></span></div>
	
	<div id="rightPanel">
		<div id="map"></div>
		<div id="map-loader"></div>
		<form id="filter">
			<div class="add">
				<?php echo CHtml::link('Добавить проблему', array('/request/add')); ?>
			</div>
			<div class="form">
				<div id="filter_form">
					<div class="row">
						<label><input name="status" type="checkbox" value="1"/>Решенные</label>
						<label><input name="status" type="checkbox" value="3"/>В работе</label>
						<label><input name="status" type="checkbox" value="2"/>Не решенные</label>
						<?php if (!Yii::app()->user->isGuest): ?>
							<label>
								<input name="my" type="checkbox" value="1" />Мои
							</label>
						<?php endif; ?>
					</div>
					<div class="row bold">За период</div>
					<div class="row date">
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
								'name'=>'from',
								'language' => Yii::app()->language,
							));
						?>
						по
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
								'name'=>'to',
								'language' => Yii::app()->language,
								'options' => array(
									'beforeShow' => "js:function(input, inst) {
										var calendar = inst.dpDiv;

										// Dirty hack, but we can't do anything without it (for now, in jQuery UI 1.8.20)
										setTimeout(function() {
											calendar.position({
												my: 'right top',
												at: 'right bottom',
												collision: 'none',
												of: input
											});
										}, 1);
									}",
								)
							));
						?>
					</div>
				</div>
				<a href="javascript:" class="toggle"><span>Развернуть фильтр</span></a>
			</div>
			<div class="map"></div>
			<?php if (!Yii::app()->user->isGuest): ?>
			
			<a href="javascript:" class="btn small widget-modal" style="background:#000;padding:3px 0;display:block">Получить код виджета</a>
			
			<?php endif; ?>
		</form>
	</div>
</div>
<!-- Layout END -->