<div class="item">
	<h2><?php echo $data->toString(); ?></h2>
	<h4><?php echo $data->address; ?></h4>
	<div class="body">
		<?php echo Text::limit_chars($data->description, 200); ?>
	</div>
	<div class="info">
		<span class="date"><?php echo CDateTime::format($data->created); ?></span>
		<a href="javascript:" onclick="Rupor.request.like(<?php echo (int)$data->id; ?>, $.proxy(function(res){if(res.status==1)$(this).find('span').text(res.count)}, this))" class="btn-like">+<span><?php echo $data->likes_count; ?></span></a>
		<span class="ctip"><span><?php echo $data->comments_count; ?></span></span>
	</div>
</div>