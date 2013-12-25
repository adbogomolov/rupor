<ul class="row">
	<li class="col1">
		<?php if ($data->user): ?>
			<?php echo $data->user->getAvatar(array('class'=>'ava ava27')); ?>
			<?php echo $data->user->getName(); ?>
		<?php else: ?>
			Аноним
		<?php endif; ?>
	</li>
	<li class="col2">
		<?php echo $data->event(); ?>
	</li>
	<li class="col3">
		<?php echo CDateTime::format($data->created); ?>
	</li>
</ul>