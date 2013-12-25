(function(w, undefined){

if (!w.Rupor) return;

var t; // update timer

Rupor.map = {
	types: {
		yandex: {
			label: 'Яндекс карта',
			value: 'yandex#map'
		},
		google: {
			label: 'Гугл карта',
			value: 'google#map'
		},
		osm: {
			label: 'OSM',
			value: 'osm#map'
		}
	},
	type: 'yandex', // default type
	api: null, // Map API (Map Provider)

	// events
	beforeUpdate: null, // Map API (Map Provider)

	getContainer: function(){ return $('#map'); },
	getLoader: function(){ return $('#map-loader'); },

	// hooks
	_hooks: {},
	addHook: function(name, fn) {
		if ($.isFunction(fn)) {
			if (!this._hooks[name]) this._hooks[name] = [];
			this._hooks[name].push(fn);
		}
	},
	callHook: function(name, params) {
		var hook = this._hooks[name];
		if (hook && hook.length>0) {
			for(var i in hook) {
				hook[i](params);
			}
		}
	},

	// Инициализация карты
	init: function(){
		var self = this, container = this.getContainer();
		if (!container.length) return;
		this.type = this.getType();
		this.api = RuporYMapProvider(); // яндекс провайдер карты
		this.api.init(container[0], {
				type: this.types[this.type].value
			}, function(){
				if (!Rupor.data.get('location') && self.api.location)
				{
					window.ymaps.geocode(self.api.location, {results: 1}).then(function(res)
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
									Rupor.map.update();
									$('#location > a').text(res.address.split(',').pop());
									Rupor.initHash();
									//window.location.reload(true);
								}
							});
						}
					});
				}
				else
				{
					self.update();
				}
			});
	},

	// Загрузить объекты
	load: function(callbackSuccess, callbackError){
		if (!this.api) return;
		var self = this;

		$.ajax({
			url: '/request/points',
			data: Rupor.data.get(),
			cache: false,
			dataType: 'json',
			beforeSend : function(){
				self.callHook('beforeLoad');
				self.showLoader();
			},
			complete: function(xhr) {
				self.hideLoader();
			},
			success: function(response){
				self.callHook('afterLoad', response.length);
				if ($.isFunction(callbackSuccess)){
					setTimeout(function(){
						callbackSuccess(response);
					}, 10);
				}
			},
			error: function(xhr) {
				if ($.isFunction(callbackError)){
					setTimeout(function(){
						callbackError(xhr);
					}, 10);
				}
			}
		});
	},

	// Обновление карты
	update: function(filter){
		var self=this;
		if (filter) {
			Rupor.data.set(filter);
		}

		self.api.geocode(Rupor.data.get('location'), {results: 1}, function(res){
			var obj = res.geoObjects.get(0);
			if (obj){
				self.api.map.setBounds(obj.properties.get('boundedBy'));
				//Rupor.data.set({location: obj.properties.get('text')});
			}
		});

		clearTimeout(t);
		t = setTimeout(function(){
			self.clear();
			self.load(function(points){
				if (!points) return;
				var l=points.length;
				while (l--) self.place(points[l]);
			}, function(xhr, status, txt){
				Rupor.box.error('Ошибка при загрузке точек');
			});
		}, 1000); // задержка при быстрых обновлениях карты
	},

	// Добавить точку на карту
	place: function(item){
		if (!this.api) return;
		this.api.placemark([item.lat, item.lng], {
			balloonContentHeader: item.title,
			hintContent: item.title,
			// custom data
			item_id: item.id,
			item_status: item.status,
			// events
			event_balloonopen: function(target){
				$.ajax({
					url: '/request/view',
					data: {id: item.id, from: 'map'},
					beforeSend: function(response){
						target.properties.set('balloonContentBody', 'Загрузка...');
					},
					success: function(response){
						target.properties.set('balloonContentBody', response);
					}
				});
			}
		});
	},
	clear: function(){
		if (this.api){
			this.api.clear();
		}
	},
	destroy: function(){
		if (this.api){
			this.api.destroy();
		}
	},
	resize: function(){
		if (this.api){
			this.api.resize();
		}
	},
	setCenter: function(coords, zoom){
		if (!this.api) return;
		this.api.map.setCenter(coords, zoom);
	},
	panTo: function(coords, callback){
		if (!this.api) return;
		this.api.map.panTo(coords, {checkZoomRange: 1});
		// this.api.map.panTo(new YMaps.GeoPoint(coords[0], coords[1]));
	},
	setZoom: function(zoom){
		if (!this.api) return;
		this.api.map.setZoom(zoom);
	},
	setType: function(type){
		if (!this.api) return;
		if (!this.types[type]) for(type in this.types) break;
		this.api.map.setType(this.types[type].value);
		$.cookie('rupor_map_type', type, {expires: 365});
	},
	getType: function(){
		var type = $.cookie('rupor_map_type');
		if (!this.types[type]) for(type in this.types) break;
		return type;
	},
	showLoader: function(){
		var c = this.getLoader();
		if (!c||!c.length) return;
		c.show();
	},
	hideLoader: function(){
		var c = this.getLoader();
		if (!c||!c.length) return;
		c.hide();
	}
}

// Yandex Map provider
function RuporYMapProvider() {

	var base = {
		url: 'http://api-maps.yandex.ru/2.0/index.xml?load=package.full&lang=ru-RU',
		options: {
			center: 0,
			zoom: 10,
			type: 'yandex#map',
			behaviors: ['default', 'scrollZoom']
		},
		_loaded: window.ymaps!=undefined,
		_inited: false
	};

	// Загрузка библиотеки для работы с картой
	base.load = function(callback){
		base._loaded ? callback() : $.getScript(base.url, function(){ callback(); base._loaded = true; });
	};

	// Инициализация
	base.init = function(container, options, callback){
		if (base._inited) return;
		base.options = $.extend(base.options, options);

		base.load(function(){

			ymaps.ready(function(){

				// Геолокация
				base.location = [ymaps.geolocation.country, ymaps.geolocation.region, ymaps.geolocation.city].join(', ');

				// Если не задан центр, берем из геолокации
				if (!base.options.center){
					base.options.center = [ymaps.geolocation.latitude, ymaps.geolocation.longitude];
					base.options.zoom = ymaps.geolocation.zoom;
				}

				// Регистрация новых типов карты
				var GoogleLayer = function() { // google
					var layer = new ymaps.Layer('http://mt0.google.com/vt/lyrs=m@176000000&hl=ru&%c', {
						projection: ymaps.projection.sphericalMercator,
						tileTransparent: true
					});
					layer.getZoomRange = function () {
						var promise = new ymaps.util.Promise();
						promise.resolve([0, 17]);
						return promise;
					};
					return layer;
				};
				ymaps.layer.storage.add('google#layer', GoogleLayer);
				ymaps.mapType.storage.add('google#map', new ymaps.MapType('Google', ['google#layer']));

				var OsmLayer = function() { // osm
					var layer = new ymaps.Layer('http://tile.openstreetmap.org/%z/%x/%y.png', {
						projection: ymaps.projection.sphericalMercator,
						tileTransparent: true
					});
					layer.getZoomRange = function () {
						var promise = new ymaps.util.Promise();
						promise.resolve([0, 17]);
						return promise;
					};
					return layer;
				};
				ymaps.layer.storage.add('osm#layer', OsmLayer);
				ymaps.mapType.storage.add('osm#map', new ymaps.MapType('OSM', ['osm#layer']));

				// Инициализация карты
				base.map = new ymaps.Map(container, base.options);

				// Элементы управления
				base.map.controls
					.add('zoomControl', {left: 5, top: 5})
					// .add('typeSelector')
					// .add('mapTools')
					.add(new ymaps.control.MiniMap({
						type: 'yandex#publicMap'
					}), {bottom: 50});

				var clusterItemLayout = ymaps.templateLayoutFactory.createClass(
					'<ymaps class="ymaps-b-cluster-tabs__menu-item[if data.isSelected] ymaps-b-cluster-tabs__menu-item_current_yes[endif]">'+
						'<ymaps class="ymaps-b-cluster-tabs__menu-item-text">$[properties.balloonContentHeader]</ymaps>'+
					'</ymaps>', {
						build: function(){
							this.constructor.superclass.build.call(this);
							this._element = $('.ymaps-b-cluster-tabs__menu-item', this.getParentElement());
							var self = this, data = this.getData();
							this._element.bind('click', function(){
								var fn = data.properties.get('event_balloonopen');
								if ($.isFunction(fn)) fn(data);
							});
							if (data.data.get('isSelected')){
								this._element.trigger('click');
							}
						},
						clear: function(){
							this._element.unbind('click');
							this.constructor.superclass.clear.call(this);
						}
					}
				);

				// Создаем глобальный кластер
				base.cluster = new ymaps.Clusterer({
					margin: [20],
					gridSize: 64,
					// clusterBalloonContentBodyLayout: "cluster#balloonCarouselContent",
					// clusterBalloonContentBodyLayout: "cluster#balloonAccordionContent",
					balloonMinWidth: 300,
					// clusterBalloonLayout: MyBalloonLayout,
					balloonMaxWidth: 600,
					clusterBalloonSidebarItemLayout: clusterItemLayout,
					clusterBalloonSidebarWidth: 200,
					clusterBalloonWidth: 500,
					clusterDisableClickZoom: true,
					// openBalloonOnClick: false,
					// mapAutoPan: true
				});

				// Кастомизируем иконку кластера в диаграмму
				base.cluster.createCluster = function(center, geoObjects) {
					var data = [0,0,0]; // green red yellow
					for (var i=0,l=geoObjects.length;i<l;i++) {
						var status = geoObjects[i].properties.get('item_status');
						status = parseInt(status, 10);
						switch(status){
							case(1): data[0]++; break; // solved
							case(2): data[1]++; break; // unsolved
							default: data[2]++; break; // in progress
						}
					}

					cluster = ymaps.Clusterer.prototype.createCluster.call(this, center, geoObjects);

					// Если есть функция для генерации кластеров
					if (base.getChart){
						cluster.options.set({
							icons: [{
								href: base.getChart(70, data),
								size: [70, 70],
								offset: [-35, -35]
							},{
								href: base.getChart(95, data),
								size: [95, 95],
								offset: [-42, -42]
							}],
							numbers: [100]
						});
					}
					return cluster;
				}

				base.map.geoObjects.add(base.cluster);

				if ($.isFunction(callback))
					callback();
			});

			base._inited = true;
		});
	}

	base.getChart = function(size, data, colors){
		size=size||50; // размер
		colors=colors||['83B928','E62828','F1D728']; // red, green, yellow
		// переводим данные в процентное соотношение, чтобы было меньше запросов
		var len=data.length, sum=0, i;
		if (len>0) {
			for(i=0;i<len;i++) sum+=data[i];
			for(i=0;i<len;i++) data[i]=Math.ceil((data[i]*100)/sum);
		}
		// берем картинку из google chart
		return 'http://chart.googleapis.com/chart?cht=pc&chs='+size+'x'+size+'&chd=t:1|'+data.join(',')+'&chco=FFFFFF,'+colors.join('|')+'&chf=bg,s,00000000';
		// можно сделать на своем сервере, пример:
		// return 'http://pandus.molparlam.ru/img/diagramms/'+data.join('-')+'.png';
	}

	base.setBounds = function(bounds){
		if (!base._inited) return;
		return base.map.setBounds(bounds);
	}

	base.getBounds = function(){
		if (!base._inited) return;
		return base.map.getBounds();
	}

	base.geocode = function(location, options, callback){
		base.load(function(){
			ymaps.geocode(location, options).then(function(response){
				callback(response);
			});
		});
	}

	base.resize = function(){
		if (!base.map) return;
		base.map.container.fitToViewport();
	};

	base.destroy = function(){
		if (!base.map) return;
		base.map.destroy();
	};

	base.clear = function(){
		if (!base.cluster) return;
		base.cluster.removeAll();
	};

	base.placemark = function(geometry, properties, options){
		if (!base.cluster) return;
		if (!properties.item_id) return;

		options = options||{};
		options.preset = Rupor.request.getIcon(properties.item_status);

		var placemark = new ymaps.Placemark(geometry, properties, options);

		if (properties.event_balloonopen)
			placemark.events.add('balloonopen', function(){
				properties.event_balloonopen(placemark);
			});
		// if (properties.event_click)
			// placemark.events.add('click', properties.event_click);

		// добавить метку в кластер
		base.cluster.add(placemark);
	};

	return base;
}

})(window);