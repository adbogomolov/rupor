<?php
	$this->pageTitle = 'Поиск по сайту';
?>
<div class="wrap">
	<h1>Поиск проблем</h1>
	
	<form style="margin:0 0 30px">
		<input type="text" name="q" placeholder="Мы ждём от вас заветных слов!" value="<?php echo $query; ?>" style="width:480px"/>
	</form>
	
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'SearchResults',
		'dataProvider'=>$dataProvider,
		'itemView'=>'//request/search/item',
		// 'enableHistory'=>true,
		// 'ajaxUpdate'=>false,
		'summaryText'=>'Показано: {start}-{end} из {count}',
		'emptyText'=>'Ничего не найдено.',
		// 'summaryText'=>"{start}&mdash;{end} из {count}",
		'template'=>'{summary} {sorter} {items} {pager}',
		'sorterHeader'=>'Сортировать по:',
		// ключи, которые были описаны $sort->attributes
		// если не описывать $sort->attributes, можно использовать атрибуты модели
		// настройки CSort перекрывают настройки sortableAttributes
		'sortableAttributes'=>array('title', 'address', 'created'),
		'pager'=>array(
			'class'=>'CLinkPager',
			'header'=>'',
			'prevPageLabel'=>'&larr;',
			'nextPageLabel'=>'&rarr;',
			// 'header'=>false,
			// 'cssFile'=>'/css/pager.css', // устанавливаем свой .css файл
			// 'htmlOptions'=>array('class'=>'pager'),
		),
	)); ?>
	
</div>