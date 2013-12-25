<?php

Yii::import('zii.widgets.CListView');

class EListView extends CListView
{
	public $pager = array(
		'class'=>'CLinkPager',
		'prevPageLabel'=>'&larr;',
		'nextPageLabel'=>'&rarr;',
		'header'=>false,
	);
}