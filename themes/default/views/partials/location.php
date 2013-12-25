<div id="location" class="block-orange">
	<a>Загрузка...</a>
	<input type="text" style="display:none"/>
	<?php if (Yii::app()->controller->route == 'site/index'): ?><div class="angle"></div><?php endif; ?>
</div>
<?php Yii::app()->clientScript->registerScriptFile('//api-maps.yandex.ru/2.0/index.xml?load=package.full&lang=ru-RU'); ?>
<?php Yii::app()->clientScript->registerScript(__FILE__, <<<JS
;(function($){
	var \$location = $('#location'), active = \$location.find('>a'), input = \$location.find('>input');
	var loc = decodeURIComponent(Rupor.data.get('location'));

	function initLocaion(str){
		var str = str||Rupor.data.get('location');
		if (!str) return;
		str = decodeURIComponent(str);
		active.text(str.split(',').pop()); // get last item from location string
		active.attr('title', str);
	}

	input.geocomplete({
		// filter: function(item){
			// if ($.inArray(item.data.kind, ['country', 'district', 'area', 'province', 'locality']) != -1)
				// return true;
		// },
		onItemSelect: function(item){
			Rupor.geocode.getFullAddress({center: item.data.center, kind: item.data.kind, name: item.value}, function(res)
			{
				if (res.main_item)
				{
					Rupor.data.set({
						location: res.address
					});

					Rupor.initHash(res.address);

					active.text(item.value);
					active.attr('title', res.address);
					input.hide();

					if (Rupor.map)
					{
						Rupor.map.update();
					}
					else
					{
						window.location.reload(true);
					}
				}
			});

			/*
			Rupor.data.set({
				location: item.data.text
			});

			Rupor.initHash(item.data.text);

			active.text(item.value);
			active.attr('title', item.data.text);
			input.hide();

			if (Rupor.map){
				Rupor.map.update();
			}else{
				window.location.reload(true);
			}*/
		}
	});
	input.blur(function(){
		input.hide();
	});
	active.click(function(){
		input.show().val(active.text()).select().focus();
		return false;
	});

	$(window).on('hashchange', function() {
		if (window.ymaps){
			ymaps.ready(function(){
				var query = decodeURIComponent(window.location.hash.substr(1));
				if (loc==query) return;
				window.ymaps.geocode(query, {results: 1}).then(function(res)
				{
					var obj = res.geoObjects.get(0);
					if (obj)
					{
						var kind = obj.properties.get('metaDataProperty').GeocoderMetaData.kind;
						var name = obj.properties.get('name');

						Rupor.geocode.getFullAddress({center: obj.geometry.getCoordinates(), "kind": kind, "name": name}, function(res)
						{
							if (res.main_item)
							{
								Rupor.data.set({
									location: res.address
								});

								if (decodeURIComponent(loc) != res.address)
								{
									if (Rupor.map){
										Rupor.map.update();
										active.text(res.address.split(',').pop());
									}else{
										window.location.reload(true);
									}
								}
							}
						});
					}
				});
			});
		}
	});

	if (window.location.hash){
		$(window).trigger('hashchange');
	}else{
		Rupor.initHash();
	}

	initLocaion();

})(jQuery);
JS
); ?>