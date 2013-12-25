<?php
	$this->pageTitle = 'Статистика';
	$this->layout='//layouts/main';
	$this->setPageState('location', true);
	
	Yii::app()->clientScript->registerScriptFile('//www.amcharts.com/lib/amcharts.js');
	
	$js_status = CJavaScript::encode($by_status);
	$js_main = CJavaScript::encode($by_location);
	
?>
<div class="wrapper">
	<h1>Статистика</h1>
	<div class="subtitle" style="margin:0"><?php
		// Location crumbs
		$crumbs = explode(',', $location);
		$crumbs_size = sizeof($crumbs);
		if ($crumbs_size > 1)
		{
			foreach($crumbs as $i => $crumb)
			{
				$crumb = trim($crumb);
				if ($i>=$crumbs_size-1){
					echo $crumb;
					break;
				}
				echo CHtml::link($crumb, '#' . $crumb);
				echo ' > ';
			}
		}
	?></div>
	
	<style>
		#stat {position:relative;}
		#stat table {}
		#stat table td, #stat table th {padding:10px;padding:10px;vertical-align:top;border-bottom:1px dashed #ccc}
		#stat table th {border-bottom:2px solid #333}
		#stat table tr:hover td {background-color:#f6f6f6}
		
		#stat_status {position:absolute;top:0;right:0;z-index:2;width:200px;height:220px;background:#f9f9f9;padding:10px;}
		#stat_main {width:800px;height:500px}
		#stat_tags {margin:0 -50px 0 0;overflow:hidden}
		#stat_tags > div {float:left;width:200px;margin:0 50px 50px 0}
		
		#stat_table_officer {width:420px;float:left}
		
		#stat_table_user {width:500px;float:right}
		#stat_table_user img {width:25px;height:25px}
	</style>
	
	<div id="content">
	<div id="stat" class="page">
		<?php if ($by_status): // Статистика по статусу ?>
		<div id="stat_status"></div>
		<?php Yii::app()->clientScript->registerScript(__FILE__ . 'status', <<<JS
		AmCharts.ready(function() {
			// PIE CHART
			var chart = new AmCharts.AmPieChart();
			chart.dataProvider = {$js_status};
			chart.hideLabelsPercent = 1;
			chart.titleField = "status";
			chart.valueField = "total";
			chart.colorField = "color";
			chart.labelText = '';
			chart.labelText = "[[percents]]%";
			chart.labelRadius = 0;
			// chart.depth3D = 5;
			// chart.angle = 35;
			// chart.innerRadius = 10;
			chart.fontFamily = 'Arial';
			// LEGEND
			var legend = new AmCharts.AmLegend();
			legend.align = "center";
			legend.markerType = "circle";
			chart.addLegend(legend);
			// WRITE
			chart.write("stat_status");
			$(chart.div).find('svg').children().eq(15).remove();
		});
JS
		); ?>
		<?php endif; ?>
		
		<?php if ($by_location): // Статистика по адресу ?>
		<div id="stat_main"></div>
		<?php Yii::app()->clientScript->registerScript(__FILE__ . 'main', <<<JS
		AmCharts.ready(function() {
			// PIE CHART
			var chart = new AmCharts.AmPieChart();
			chart.dataProvider = {$js_main};
			chart.titleField = "location";
			chart.valueField = "total";
			chart.labelText = "[[title]]";
			chart.labelRadius = 5;
			// chart.outlineColor = "#FFFFFF";
			// chart.outlineAlpha = 0.8;
			// chart.outlineThickness = 2;
			// this makes the chart 3D
			chart.depth3D = 15;
			chart.angle = 35;
			chart.groupedPulled = 1;
			chart.fontFamily = 'Arial';
			chart.backgroundColor = 0;
			chart.radius = 220;
			chart.innerRadius = 50;
			chart.addListener("pullOutSlice", function(e){
				Rupor.data.set('location', e.dataItem.dataContext.addr);
				window.location.hash = e.dataItem.dataContext.addr;
				window.location.reload(true);
			});
			// LEGEND
			var legend = new AmCharts.AmLegend();
			legend.align = "center";
			legend.markerType = "circle";
			chart.addLegend(legend);
			// WRITE
			chart.write("stat_main");
			$(chart.div).find('svg').children().eq(15).remove();
		});
JS
		); ?>
		<?php else: ?>
		<div class="empty">Статистика не доступна</div>
		<?php endif; ?>
		
		<br/>
		<br/>
		
		<?php if ($by_tags): // Статистика по темам ?>
		<div id="stat_tags">
			<?php foreach($by_tags as $tag): ?>
			<?php
				if (empty($tag['data'])) continue;
				$id = 'stat_tag'.$tag['id'];
				$js_data = CJavaScript::encode($tag['data'])
			?>
			<div>
				<h3><?php echo $tag['name']; ?></h3>
				<div style="width:100%;height:150px" id="<?php echo $id; ?>"></div>
			</div>
			<?php Yii::app()->clientScript->registerScript(__FILE__ . $id, <<<JS
			AmCharts.ready(function() {
				// PIE CHART
				var chart = new AmCharts.AmPieChart();
				chart.dataProvider = {$js_data};
				chart.hideLabelsPercent = 1;
				chart.titleField = "status";
				chart.valueField = "total";
				chart.colorField = "color";
				chart.labelText = "[[value]]";
				chart.labelRadius = 0;
				// WRITE
				chart.write("{$id}");
				$(chart.div).find('svg').children().eq(15).remove();
			});
JS
			); ?>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<table id="stat_table_officer">
		<tr>
			<th></th>
			<th>Организация</th>
			<th>Решено</th>
			<th>В работе</th>
		</tr>
		<?php $i=0; foreach($officers as $officer): $i++; ?>
		<tr>
			<td><?php echo $i; ?></td>
			<td>
				<?php echo $officer->post; ?>
			</td>
			<td class="center">0</td>
			<td class="center"><?php echo $officer->count; ?></td>
		</tr>
		<?php endforeach; ?>
		</table>
		
		<table id="stat_table_user">
		<tr>
			<th></th>
			<th>Пользователь</th>
			<th>Рейтинг</th>
			<th>Проблем</th>
			<th>Комменты</th>
		</tr>
		<?php $flag=0; $i=0; foreach($users as $user): $i++;
            if ($i==1 && $user->rating==0){ $flag=1; }
            if (($user->request_count>0 || $user->comment_count>0) && $flag==0){
                ?>
		<tr>
			<td><?php echo $i; ?></td>
			<td>
				<?php echo $user->getAvatar(); ?>
				<?php echo $user->getName(); ?>
			</td>
			<td class="center"><?php echo $user->rating; ?></td>
<td class="center"><?php echo $user->request_count; 
    ?></td>
<td class="center"><?php echo $user->comment_count; 
    ?></td>
		</tr>
		<?php
            }
        endforeach; ?>
		</table>
		
		<div class="clear"></div>
	</div>
	</div>
</div>