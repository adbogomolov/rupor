<?php
	$this->pageTitle = 'Добавление проблемы';
?>

<div class="form wrap span7">

	<h1>Добавление проблемы</h1>

	<?php if (Yii::app()->user->isGuest): ?>

		<div style="margin:0 0 20px;color:#d43a33">
			<p>В соответствии с <a href="/page/legal">ФЗ №59</a> обязательными к рассмотрению органами государственной власти являются сообщения с указанием фамилии,
			имени и электронного адреса пользователя.</p>
			<font color="black">Ваши фамилия и имя:</font> не определено – необходима <a href="/user/register">регистрация</a>.
			<br/>
			<font color="black">Ваш электронный адрес:</font> не определено – необходима <a href="/user/register">регистрация</a>.
		</div>

	<?php else: ?>

		<?php $user = Yii::app()->user->model; ?>
		<?php if (!$user->first_name || !$user->last_name): ?>
		<div style="margin:0 0 20px;color:#d43a33">
			<p>В соответствии с <a href="/page/legal">ФЗ №59</a> обязательными к рассмотрению органами государственной власти являются сообщения с указанием фамилии,
			имени и электронного адреса пользователя.</p>
			<font color="black">Ваши фамилия и имя:</font> не полные данные – необходимо заполнить в <a href="/user/settings">настройках</a>.
		</div>
		<?php endif; ?>

	<?php endif; ?>

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'FormRequest',
		'enableAjaxValidation' => true,
		'clientOptions'=> array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
			'afterValidate'=>"js:function(form, data, hasError){
				if (!hasError){
					if (data.status==1){
						Rupor.box.notice(data.message, {hideDelay: 1500}, function(){
							location.href = data.returnUrl;
						});
					}else{
						Rupor.box.error(data.message);
					}
				}
				return false;
			}"
		),
	)); ?>

	<div class="row">
		<div class="hint">
			Опишите проблему максимально подробно.
			Это поможет ответственным лицам быстрее решть вашу проблему.
			Старайтесь не придавать сообщению эмоциональной окраски.
		</div>
		<?php echo $form->textArea($model, 'description', array('class'=>'span7', 'style'=>'height:200px')) ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', array('placeholder'=>'Опишите проблему в двух словах', 'class'=>'span7')) ?>
		<?php echo $form->error($model, 'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'address'); ?>
		<p id="request_hint">Не выбрано</p>
		<?php echo $form->hiddenField($model, 'address'); ?>
		<?php echo $form->hiddenField($model, 'lat'); ?>
		<?php echo $form->hiddenField($model, 'lng'); ?>
		<?php echo $form->textField($model, 'address', array('placeholder'=>'Выберите адрес из списка или на карте', 'name'=>'geocomplete', 'id'=> 'request_geocomplete', 'class'=>'span7')); ?>
		<div id="request_map" class="span7 " style="width:554px;height:250px"></div>
		<?php echo $form->error($model, 'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'tags'); ?>
		<?php $this->widget('ext.chosen.ChosenWidget', array(
			'model' => $model,
			'attribute' => 'tags',
			'data' => $tags,
			'htmlOptions' => array(
				'data-placeholder'=>'Выберите наиболее подходящие темы',
				'style'=>'width:554px',
				'multiple'=>true,
				'encode'=>false,
			),
		)); ?>
		<?php echo $form->error($model, 'tags'); ?>
	</div>

	<div id="request_officers" class="row officers">
		<?php echo $form->labelEx($model, 'officers'); ?>
		<div>
			<div class="empty">Выберите тему и адрес</div>
		</div>
	</div>

	<div class="row" style="width:316px">
	<?php $this->widget('ext.uploader.UploaderWidget', array(
		'id' => 'RequestUploader',
		'options' => array(
			'request' => array(
				'endpoint' => '/request/upload'
			),
			'validation' => array(
				'itemLimit' => 5
			),
			'classes' => array(
				'button' => 'qq-upload-button'
			),
			'text' => array(
				'uploadButton'=>'Добавить фотографию'
			),
			'callbacks' => array(
				'onComplete' => "js:function(id, name, response){
					if (response.success){
						$('#FormRequest').append('<input type=\"hidden\" name=\"FormRequest[files]['+response.id+']\" value=\"'+response.hash+'\"/>');
					}
				}"
			)
		)
	)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'videos'); ?>
		<?php echo $form->textField($model, 'videos[]', array('placeholder'=>'http://', 'class'=>'span4')) ?>
		<br/>
		<a href="javascript:" onclick="var input=$(this).parent().find('input').last();input.after(input.clone().val('').focus());">Еще ссылку</a>
		<?php echo $form->error($model, 'videos'); ?>
	</div>

	<br/>

	<?php if (!Yii::app()->user->isGuest): ?>
	<div class="row">
		<label>
			<?php echo $form->checkBox($model, 'notify_email', array('checked'=>1)) ?>
			Получать уведомления по почте
		</label>
	</div>
	<br/>
	<?php endif; ?>

	<div class="row buttons">
		<button type="submit" class="btn btn-big btn-black">Добавить проблему</button>
	</div>

<?php $this->endWidget(); ?>

</div>

<?php Yii::app()->clientScript->registerScriptFile('//api-maps.yandex.ru/2.0/index.xml?load=package.full&lang=ru-RU'); ?>
<?php Yii::app()->clientScript->registerScript(__FILE__, <<<JS
(function(){

	var fid = 'FormRequest', map, t,
		form = $('#'+fid),
		\$map = $('#request_map', form),
		officers = $('#request_officers', form),
		address_input = $('#request_geocomplete', form),
		address_hint = $('#request_hint', form),
		address = $('#'+fid+'_address', form),
		lat = $('#'+fid+'_lat', form),
		lng = $('#'+fid+'_lng', form),
		tags = $('#'+fid+'_tags', form);

	// check officers
	function checkOfficers(){
		clearTimeout(t);
		t = setTimeout(function(){
			var node = officers.find('>div');
			node.text('Загрузка...');
			$.ajax({
				url: '/request/officers',
				data: {tags: tags.val(), address: address.val()||Rupor.data.get('location'), point: [lat.val(), lng.val()]},
				dataType: 'json',
				success: function(res){
					if (!res||!res.length) return;
					node.text('');
					for(var i in res){
						var row = $('<span>'+res[i].post+' <a href="javascript:" class="remove">x</a>; </span>');
							row.find('.remove').click(function(){ $(this).parent().remove(); return false; });
							row.append('<input type="hidden" name="'+fid+'[officers][]" value="'+res[i].id+'"/>');
						node.append(row);
					}
				}
			});
		}, 700);
	}

	// set placemark to map
	function setPlacemark(center, stop){
		if (!window.ymaps) return;
		var placemark = new ymaps.Placemark(center);
		map.geoObjects.each(function(context) { map.geoObjects.remove(context); });
		map.geoObjects.add(placemark);

		ymaps.geocode(center, {results: 1}).then(function(res){
			var geoObject = res.geoObjects.get(0);
			if (geoObject){
				if (!stop){
					center[0] = parseFloat(center[0]);
					center[1] = parseFloat(center[1]);

                    var map_geo = geoObject.properties.get('text');

					 var map_geo2 = '';
					 var parts_geo = map_geo.split(',');
					 var map_geo_all;
					 var map_geo_all2;
					 var parts_geo_itog;
					 var map_geo_itog = parts_geo[parts_geo.length-1];
					 var parts_geo_itog = map_geo.split(',');
					 //map_geo_all = parts_geo[0];
					 var myGeocoder;
					 var street;
					 var j = 0;
					 var j_cur = 0;
					 var j_max = parts_geo.length-2;

					 //map_geo_itog = parts_geo[j_max];
					 //for (var j = 0; j < j_max; j++) {
					 j_cur = j_max - j;
					 map_geo_all = parts_geo[0];
					 //for (var i = 1; i <= j_cur; i++) {
					 //   map_geo_all = map_geo_all + ',' + parts_geo[i];
					 //}
					 //parts_geo_itog[j]=map_geo_all;
					 //map_geo_all = parts_geo.slice(0,j_cur);
					 parts_geo_itog[0] = map_geo;
					 //if (j == 0) {
					 //address_hint.text(map_geo_all);
					 //}
					 //}
					 //alert (map_geo);

					 //alert (parts_geo_itog[0]);
					 myGeocoder = ymaps.geocode(parts_geo_itog[0]);
					 myGeocoder.then(
									 function (res) {
									 street = res.geoObjects.get(0);
									 map_geo2 = street.properties.get('text');
									 parts_geo_itog = map_geo2.split(',');
									 map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
									 parts_geo_itog[1] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
									 map_geo_itog = map_geo22;//+','+map_geo_itog;
									 //alert (map_geo22);
									 //alert (parts_geo_itog[1]);
									 address_hint.text(map_geo_itog);
									 if (parts_geo_itog[1]!='') {
									 myGeocoder = ymaps.geocode(parts_geo_itog[1]);
									 myGeocoder.then(
													 function (res) {
													 street = res.geoObjects.get(0);
													 map_geo2 = street.properties.get('text');
													 parts_geo_itog = map_geo2.split(',');
													 map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
													 parts_geo_itog[2] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
													 map_geo_itog = map_geo22+','+map_geo_itog;
													 //alert (map_geo22);
													 //alert (parts_geo_itog[2]);
													 //address_hint.text(map_geo_itog);
													 if (parts_geo_itog[2]!='') {
													 myGeocoder = ymaps.geocode(parts_geo_itog[2]);
													 myGeocoder.then(
																	 function (res) {
																	 street = res.geoObjects.get(0);
																	 map_geo2 = street.properties.get('text');
																	 parts_geo_itog = map_geo2.split(',');
																	 map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
																	 parts_geo_itog[3] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
																	 map_geo_itog = map_geo22+','+map_geo_itog;
																	 //alert (map_geo22);
																	 //alert (parts_geo_itog[3]);
																	 if (parts_geo_itog[3]!='') {
																	 myGeocoder = ymaps.geocode(parts_geo_itog[3]);
																	 myGeocoder.then(
																					 function (res) {
																					 street = res.geoObjects.get(0);
																					 map_geo2 = street.properties.get('text');
																					 parts_geo_itog = map_geo2.split(',');
																					 map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
																					 parts_geo_itog[4] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
																					 map_geo_itog = map_geo22+','+map_geo_itog;
																					 //alert (map_geo22);
																					 //alert (parts_geo_itog[4]);
																					 if (parts_geo_itog[4]!='') {
																					 myGeocoder = ymaps.geocode(parts_geo_itog[4]);
																					 myGeocoder.then(
																									 function (res) {
																									 street = res.geoObjects.get(0);
																									 map_geo2 = street.properties.get('text');
																									 parts_geo_itog = map_geo2.split(',');
																									 map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
																									 parts_geo_itog[5] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
																									 map_geo_itog = map_geo22+','+map_geo_itog;
																									 //alert (map_geo22);
																									 //alert (parts_geo_itog[4]);
                                                                                                     if (parts_geo_itog[5]!='') {
                                                                                                     myGeocoder = ymaps.geocode(parts_geo_itog[5]);
                                                                                                     myGeocoder.then(
                                                                                                                     function (res) {
                                                                                                                     street = res.geoObjects.get(0);
                                                                                                                     map_geo2 = street.properties.get('text');
                                                                                                                     parts_geo_itog = map_geo2.split(',');
                                                                                                                     map_geo22 = parts_geo_itog[parts_geo_itog.length-1];
                                                                                                                     parts_geo_itog[6] = parts_geo_itog.slice(0,parts_geo_itog.length-1).join(',');
                                                                                                                     map_geo_itog = map_geo22+','+map_geo_itog;
                                                                                                                     //alert (map_geo22);
                                                                                                                     //alert (parts_geo_itog[4]);
                                                                                                                     address_hint.text(map_geo_itog);
                                                                                                                     address.val(map_geo_itog);
                                                                                                                     lat.val(center[0]);
                                                                                                                     lng.val(center[1]);
                                                                                                                     checkOfficers();
                                                                                                                     });
                                                                                                     } else {
																									 address_hint.text(map_geo_itog);
																									 address.val(map_geo_itog);
																									 lat.val(center[0]);
																									 lng.val(center[1]);
																									 checkOfficers();
                                                                                                     }
																									 });
																					 } else {
																					 address_hint.text(map_geo_itog);
																					 address.val(map_geo_itog);
																					 lat.val(center[0]);
																					 lng.val(center[1]);
																					 checkOfficers();
																					 }
																					 });
																	 } else {
																	 address_hint.text(map_geo_itog);
																	 address.val(map_geo_itog);
																	 lat.val(center[0]);
																	 lng.val(center[1]);
																	 checkOfficers();
																	 }
																	 });
													 } else {
													 address_hint.text(map_geo_itog);
													 address.val(map_geo_itog);
													 lat.val(center[0]);
													 lng.val(center[1]);
													 checkOfficers();
													 }
													 });
									 } else {
										address_hint.text(map_geo_itog);
										 address.val(map_geo_itog);
										 lat.val(center[0]);
										 lng.val(center[1]);
										 checkOfficers();
									 }
									 });

					//address_hint.text(map_geo_itog);

					address.val(geoObject.properties.get('text'));
					lat.val(center[0]);
					lng.val(center[1]);
					checkOfficers();
				}
			}
		});
	}

	// init map
	ymaps.ready(function(){
		ymaps.geocode(Rupor.data.get('location'), {results: 1}).then(function(res){
			var obj = res.geoObjects.get(0);
			map = new ymaps.Map(\$map[0], {zoom:10, center:0, type:'yandex#map', behaviors:['default', 'scrollZoom']});
			map.setBounds(obj.geometry.getBounds(), {checkZoomRange: true});
			map.controls.add('zoomControl', {left: 5, top: 5});
			map.events.add('click', function(e) {
				setPlacemark(e.get('coordPosition'));
			});
			// setPlacemark(obj.geometry.getCoordinates(), true);
		});
	});

	// init geocomplete
	address_input.geocomplete({
		filter: function(item){
			if ($.inArray(item.data.kind, ['street', 'house']) != -1)
				return true;
		},
		beforeUseConverter: function(value){
			return Rupor.data.get('location') + ', ' + value;
		},
		onItemSelect: function(item){
			map.setBounds(item.data.bounds, {checkZoomRange: true});
			address_input.val(item.value);
			address.val(item.data.text);
			setPlacemark(item.data.center);
		}
	});

	address_input.focus(function(){
		window.setTimeout(function(){
			address_input.select();
		}, 100);
	});

	tags.on('change', function(){
		checkOfficers();
	});

}).call(this);
JS
); ?>