<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/base.js');
	Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/main.css');
	Yii::app()->clientScript->registerLinkTag('shortcut icon', 'image/x-icon', Yii::app()->theme->baseUrl.'/favicon.ico');
?><!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>" dir="<?php echo Yii::app()->locale->orientation; ?>">
<head>
	<meta http-equiv="Content-type" content="text/html;charset=<?php echo Yii::app()->charset; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0">
	<script>window.Rupor = <?php echo CJavaScript::encode($this->js); ?></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body class="<?php $layout = explode('/', Yii::app()->controller->layout); echo end($layout); ?>_layout <?php echo str_replace('/','_',Yii::app()->controller->uniqueId); ?>" id="<?php echo str_replace('/', '_', Yii::app()->controller->route); ?>">
<noscript>Для работы сайта требуется JavaScript. Пожалуйста, включите его в браузере и перезагрузите страницу.</noscript>
<?php echo $content; ?>
</body>
</html>