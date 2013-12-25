<div class="item">
	<?php echo CHtml::link($data->title, $data->getHref()); ?>
	<?php if ($data->status == Request::STATUS_INPROGRESS): ?>
	<span style="background:#ffc;padding:1px 7px">В работе</span>
	<?php endif; ?>
	(<?php echo $data->address; ?>)
	<p><?php echo CDateTime::format($data->created); ?></p>
</div>