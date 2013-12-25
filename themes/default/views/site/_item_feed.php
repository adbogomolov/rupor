<?php
	$image = $data->getFiles(array(
		'condition' => 'mime_major = "image"',
		'limit'=>1,
	));
?>
<?php if ($image): ?>
<div class="item with-image">
<?php echo CHtml::image($image[0]->getThumbUrl(300)); ?>
<?php else: ?>
<div class="item">
<?php endif; ?>
<div>
	<h4><?php echo $data->title; ?></h4>
	<div class="address">
		<?php echo $data->address; ?>
	</div>
	<div class="body">
		<?php echo CHtml::link(Text::limit_chars(strip_tags($data->description), 100), $data->getHref()); ?>
	</div>
	<div class="info">
		<div class="counters">
			<a href="javascript:" onclick="Rupor.request.like(<?php echo (int)$data->id; ?>, $.proxy(function(res){if(res.status==1)$(this).find('span').text(res.count)}, this))" class="btn-like">+<span><?php echo $data->likes_count; ?></span></a>
			<span class="ctip"><span><?php echo $data->comments_count; ?></span></span>
		</div>
		<div class="date">
			<?php echo CDateTime::format($data->lastactive); ?>
		</div>
	</div>
</div>
</div>