<?php
	Yii::app()->clientScript->registerScriptFile('//platform.twitter.com/widgets.js');
	Yii::app()->clientScript->registerScriptFile('//vk.com/js/api/openapi.js');
	Yii::app()->clientScript->registerScript(__FILE__.'vk', '
VK.init({apiId: 3728141, onlyWidgets: true});
VK.Widgets.Like("vk_like", {type: "mini"});');
?>
<div class="share-buttons" style="overflow:hidden;height:22px;">
	<!-- Vkontakte button -->
	<div id="vk_like" style="float:left"></div>
	<!-- Facebook button -->
	<iframe src="http://www.facebook.com/plugins/like.php?layout=button_count&href=<?php echo Yii::app()->createAbsoluteUrl(Yii::app()->request->url); ?>" style="border:none; overflow:hidden; width:120px; height:25px" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
	<!-- Twitter button -->
	<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a>
</div>