<div style="">
	<h3><?php echo $model->title; ?></h3>
	
	<?php echo $model->description; ?>
	
	<br/>
	<br/>
	<?php if ($model->files): ?>
	<?php foreach($model->files as $file): ?>
		<?php echo $file->toString(); ?>
	<?php endforeach; ?>
	<br/>
	<br/>
	<?php endif; ?>
	
	<a href="javascript:" onclick="Rupor.request.like('<?php echo $model->id; ?>', event);" class="btn-like">+<span><?php echo $model->likes_count; ?></span></a>
	
	<br/>
	<br/>
	
	<a href="<?php echo $model->getHref(); ?>">Подробнее</a>
	
	<br/>
	<br/>
</div>