<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
	<?php echo $this->renderPartial('//partials/flash'); ?>
	<?php echo $content; ?>
</div>
<?php $this->endContent(); ?>