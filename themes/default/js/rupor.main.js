if (!window.Rupor) Rupor = {};

Rupor.main = new (function() {
	
	var base = this,
		inited = false,
		
		opts = {
			selectors: {
				layers: '#layers',
				layout: '#layout',
				filter: '#filter',
				tags: '#tags'
			},
			splitHoverClass: '__hover',
			splitMoveClass: '__move',
			splitOverlayClass: '__overlay',
			splitSize: 250, // split part size
			splitDelay: 0 // @todo: split slide effect
		},
		
		$layout, $layers, $filter, $tags, $splitter, $ghost, $panels=[];
	
	this.opts = opts;
	
	this.init = function(opts) {
		
		this.opts = $.extend(this.opts, opts);
		
		$layout = $(this.opts.selectors.layout);
		$layers = $(this.opts.selectors.layers);
		$filter = $(this.opts.selectors.filter);
		$tags = $(this.opts.selectors.tags);
		$splitter = $('#splitter', base.$layout);
		$panels = [$splitter.prev(), $splitter.next()];
		
		// инициализация карты
		Rupor.map.addHook('afterLoad', function(total){
			base.feed.reset();
			base.feed.update({total: total});
		});
		Rupor.map.init();
		
		// инициализация блоков
		base.initLayout();
		base.initLayers();
		base.initFilter();
		base.initTags();
		base.resize();
		
		inited = true;
	}
	
	this.reset = function() {
		if (!inited) return;
		$filter.find('form').reset();
	}
	
	this.resize = function() {
		if (!$layout.length) return;
		$layout.height($(window).height()-$layout.offset().top);
		$panels[1].width($layout.width()-Math.max($panels[0].width(),0)-$splitter.width());
		Rupor.map.resize();
	}
	
	this.getPos = function(w) {
		if (!w) w = $panels[0].width();
		return Math.round(w/opts.splitSize);
	}
	
	this.splitPos = function() {
		return this.getPos($panels[0].width());
	}
	
	this.splitTo = function(pos, delay) {
		var width = pos*opts.splitSize;
		if (pos>1)
			width+=15;
		$panels[0].width(width);
		// $panels[0].animate({width: width}, delay||opts.splitDelay, 'linear', function() {
			base.resize();
		// });
	}

	// сверху карты налаживвается невидимый слой
	this.showOverlay = function() {
		var $overlay;
		$.each($panels, function(i){
			$overlay = $('<div class="'+ opts.splitOverlayClass +'" style="background:#fff;position:absolute;top:0;left:0;width:100%;height:100%;z-index:999"/>')
			$overlay.css({opacity:0});
			$(this).append($overlay);
		});
	}
	
	this.hideOverlay = function() {
		$layout.find('.'+opts.splitOverlayClass).remove();
	}
	
	// Инициализация Макета
	this.initLayout = function () {
		
		if (!$layout.length) return;
		
		var self=this;
		
		// start drag
		function moveStart(e) {
			base.showOverlay(); // show overlays
			// create ghost
			$ghost = $('<div/>').insertAfter($splitter);
			$ghost.css({background: '#eee', height: '100%', opacity:.7, zIndex:1000, position:'absolute', left:0});
			$('body').css({cursor:'e-resize'});
		}
		
		// move drag
		function moveStep(e) {
			var pos = Math.round(e.pageX/opts.splitSize);
			var w = pos * opts.splitSize;
			if (!pos) w = $splitter.width();
			$ghost.css({width: w});
		}
		
		// end drag
		function moveEnd(e) {
			if ($ghost!=null) {
				base.hideOverlay(); // hide overlays
				
				if ($ghost.width() == 0){ // click
					base.splitTo(base.splitPos()>=1?0:1);
				}else{
					base.splitTo(base.getPos($ghost.width()));
				}
				
				// rebuild feed items
				self.feed.rebuild();
				
				$ghost.remove();
				$ghost=null;
			}
			$('body').css({cursor:'default'});
		}
		
		// splitter hover
		$splitter.on('mouseenter', function(){
			$(this).addClass(opts.splitHoverClass);
		}).on('mouseleave', function(){
			$(this).removeClass(opts.splitHoverClass);
		});
		
		// split drag event
		$splitter.find('span').on('mousedown.rupor', function(e){
			if (e.target != this) return;
			$(this).addClass(opts.splitMoveClass);
			moveStart(e);
			$(document).on('mousemove.rupor', function(e) {
				if (document.selection)
					document.selection.empty();
				else if (window.getSelection())
					window.getSelection().removeAllRanges();
				e.preventDefault();
				moveStep(e);
			});
			$(document).on('mouseup.rupor', function(e) {
				$(document).unbind('mousemove.rupor mouseup.rupor');
				$splitter.removeClass(opts.splitHoverClass+' '+opts.splitMoveClass);
				moveEnd(e);
			});
		});
		
		$(window).on('resize.rupor', function(){
			base.resize();
		});
	};

	// Инициализация слоев
	this.initLayers = function() {
		if (!$layers.length) return;
		var t, speed = 300, $list = $layers.find('ul');
		$layers.bind('mouseenter', function() {
			clearTimeout(t);
			$list.slideDown(speed);
		}).bind('mouseleave', function() {
			t = setTimeout(function() {
				$list.slideUp(speed);
			}, 300);
		});
	};
	
	// Инициализация фильтра
	this.initFilter = function(data) {
		if (!$filter.length) return;
		
		data = $.extend(Rupor.data.$, data);
		
		var _update = function(){
			delete data['to'];
			delete data['from'];
			delete data['my'];
			data['status'] = [];
			
			$.each($filter.serializeArray(), function(i, e){
				
				if ($.isArray(data[e.name]))
					data[e.name].push(e.value);
				else
					data[e.name] = e.value;
			});
			
			Rupor.map.update(data);
		};
		
		$filter[0].reset();
		
		// init form data
		$.each(data, function(name, value){
			if ($.isArray(value)){
				for(var i in value){
					var el = $('[name="'+name+'"][value="'+value[i]+'"]', $filter);
					if (el.length) el[0].checked = true;
				}
			}else{
				$('[name='+name+']:checkbox', $filter).attr('checked', true);
				$('[name='+name+']:not(:checkbox)', $filter).val(value);
			}
		});
		
		// build map types list
		var $map = $filter.find('.map');
		$.each(Rupor.map.types, function(type, opts){
			var label = opts.label||type;
			$map.append($('<a href="javascript:" data-map-type="'+type+'">'+label+'</a>').click(function(){
				Rupor.map.setType($(this).attr('data-map-type'));
				$map.find('[data-map-type]').removeClass('active');
				$(this).addClass('active');
				return false;
			}));
		});
		
		$map.find('[data-map-type='+Rupor.map.type+']').addClass('active');
		
		// form change event
		$filter.find('input,select').change(_update);
		
		// add request event
		// $filter.find('.add > a').on('click', function(){
			// Rupor.box.wide({
				// href: $(this).attr('href'),
				// closeBtn: false
			// });
			// return false;
		// });
		
		// toggle filter form event
		$filter.find('.toggle').on('click', function(){
			var $toggle = $(this), speed = 300;
			var $filter_form = $filter.find('#filter_form');
			if ($filter.hasClass('open')){
				$filter_form.slideUp(speed, function(){
					$filter.removeClass('open');
					$toggle.find('span').text('Развернуть фильтр');
					$.removeCookie('rupor_filter');
				});
			} else {
				$filter_form.slideDown(speed, function(){
					$filter.addClass('open');
					$toggle.find('span').text('Свернуть фильтр');
					$.cookie('rupor_filter', 1, {expires: 365});
				});
			}
			return false;
		});
		
		if ($.cookie('rupor_filter')==1) {
			$filter.addClass('open');
			$filter.find('.toggle>span').text('Свернуть фильтр');
		}
	};
	
	// Инициализация тэгов
	this.initTags = function() {
		if (!$tags.length) return;
		
		// private methods
		function _getActiveTags(){
			var tags = [];
			$tags.find('a[data-tag].active').each(function(){
				var id = parseInt($(this).attr('data-tag'), 10);
				if (id>0) tags.push(id);
			});
			return tags;
		}
		function _updateCounter(n){
			n = n || _getActiveTags().length;
			$tags.find('.total > span').text(n);
		}
		
		var active = Rupor.data.get('tags');
		
		// инициализацтя активных
		if (active && active.length > 0) {
			for(var i in active) {
				var id = active[i];
				$tags.find('a[data-tag='+id+']').addClass('active');
			}
			_updateCounter(active.length);
		}
		
		// выбрать группу тэгов
		$tags.find('h2[data-tag]').on('click', function(){
			var tags = $(this).next().find('a[data-tag]');
			// tags.removeClass('active');
			tags.trigger('click');
		});
		
		// выбрать тэг
		$tags.find('a[data-tag]').on('click', function(){
			$(this).toggleClass('active');
			var tags = _getActiveTags();
			Rupor.map.update({tags: tags});
			_updateCounter(tags.length);
		});
		
		// выбрать все
		$tags.find('.select').on('click', function(){
			var data = {tags: []};
			$tags.find('[data-tag]')
				.each(function(){ data.tags.push($(this).attr('data-tag')); })
				.addClass('active');
			Rupor.map.update(data);
		});
		
		// свернуть/развернуть
		$tags.find('.toggle').on('click', function(){
			var $toggle = $(this),
				$ol = $tags.find('ol'),
				properties = {},
				tags = [],
				speed = 300;
			
			if ($tags.hasClass('open')){
				properties.height = 60;
				$ol.animate(properties, speed, function(){
					$tags.removeClass('open');
					$toggle.find('span').text('Развернуть меню');
				});
			} else {
				var h = $ol.height()
				$ol.css({height: 'auto'});
				properties.height = $ol.height();
				$ol.css({height: h}).animate(properties, speed, function(){
					$tags.addClass('open');
					$toggle.find('span').text('Свернуть меню');
				});
			}
			return false;
		});
	};
	
	// Лента активности
	this.feed = {
		$: function() { if (!this._inited) this.init(); return $('#feed'); }, // base container
		$panel: function() { return $panels[0]; }, // panel container
		_debug: function() {if (this.debug && 'console' in window) window.console.log.apply(window.console, arguments);},
		_inited: false,
		_loading: false,
		debug: false,
		page: 1,
		scrollTo: function(pos,speed) {
			this.$panel().animate({scrollTop: pos||0}, speed||200);
		},
		init: function() {
			var self = this;
			this._debug('init');
			// bind $panel infinity scroll
			this.$panel().html('<div id="feed"></div>').bind('scroll.rupor', function(e){
				var scrollTop = $(this).scrollTop(),
					scrollHeight = $(this).prop('scrollHeight') - $(this).height() - 100;
				if (scrollTop && scrollTop >= scrollHeight && !self._loading){
					self._debug('load page ('+self.page+')');
					self.update({offset: self.page*10}, function(response){
						if (response.length>0) self.page++;
					});
				}
				e.preventDefault();
			});
			this._inited = true;
			this.reset();
		},
		reset: function() {
			this.page=1; // reset page
			this.scrollTo(0); // scroll to top
			this.$().text(''); // clear content
		},
		rebuild: function() {
			this._debug('rebuild');
			var pos = base.getPos(), $container = this.$(),
				$li = $container.find('li'), $wrap=$('<div/>'), $col,
				col = pos>0?Math.ceil($li.length/pos):0;
			// if visible panel
			if (pos>0) {
				var i=0;
				$li.each(function(){
					if (!i||i==col) { $col = $('<ul/>').appendTo($wrap); i=0; } i++;
					$col.append($(this));
				});
				$container.html($wrap);
			}
			// show/hide panel
			base.splitTo($li.length?pos||1:0);
		},
		load: function(data, callbackSuccess, callbackError) {
			var self = this, $container=this.$();
				self._loading = true;
			$.ajax({
				url: '/request/points',
				data: $.extend({}, Rupor.data.get(), data||{}),
				cache: false,
				dataType: 'json',
				beforeSend : function(){
					self._debug('xhr', data);
					$container.addClass('loading');
					$container.append('<div class="loader">Загрузка...</div>');
				},
				complete: function() {
					$container.removeClass('loading');
					$container.find('.loader').remove();
				},
				success: function(response){
					if ($.isFunction(callbackSuccess)){
						setTimeout(function(){
							callbackSuccess(response);
							self._loading = false;
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
		update: function(data, callbackSuccess, callbackError) {
			
			var self=this, $container=this.$(), t;
			data = data||{};
			data.by = 'feed';
			
			// first page load
			if (data.offset==0||data.total==0){
				this.reset();
			}
			
			return this.load(data, function(response) {
				if (!response)
					return;
				var $ul = $('<ul/>').appendTo($container), $li;
				$.each(response, function(){
					
					// base feed item template
					$li = $('\
						<li class="item">\
						<div>\
							<h4>'+this.title+'</h4>\
							<div class="address">'+this.address+'</div>\
							<div class="body">'+this.description+'</div>\
							<div class="event">'+this.event+'</div>\
							<div class="info">\
								<div class="counters">\
									<a href="javascript:void(0)" onclick="Rupor.request.like('+parseInt(this.id, 10)+', $.proxy(function(res){if(res.status==1)$(this).find(\'span\').text(res.count)}, this));return false" class="btn-like">+<span>'+this.likes_count+'</span></a>\
									'+ (parseInt(this.comment_count,10)>0 ? '<span class="ctip"><span>'+this.comment_count+'</span></span>' : '') +'\
								</div>\
								<div class="date">'+this.date+'</div>\
							</div>\
						</div>\
						</li>'
					).css({cursor:'pointer'});
					
					// item data
					$li.data({
						url: this.url,
						image: this.image,
						coords: [this.lat, this.lng]
					});
					
					// preload image if exists
					if (this.image){
						var $img = $('<img src="'+this.image+'" alt=""/>')
						.appendTo($li)
						.css({opacity: 0})
						.on('load', function(){
							$(this).parent().addClass('with-image');
							$(this).css({opacity: 0.5});
						})
						.on('error', function(){
							$img.remove();
						});
					}
					
					// event: click (open request page)
					$li.click(function(e){
						if (e.target.nodeName=='A')
							return false;
						location.href=$(this).data('url');
					});
					
					// event: mouseover (center map)
					$li.mouseenter(function(){
						$(this).addClass('hover');
						var data = $(this).data();
						if (Rupor.map) {
							clearTimeout(t);
							t = setTimeout(function(){
								Rupor.map.setCenter(data.coords, 16)
							}, 1000);
						}
					});
					
					$li.mouseleave(function(){
						$(this).removeClass('hover');
						clearTimeout(t);
					});
					
					$ul.append($li);
				});
				
				// rebuild panel items
				self.rebuild();
				
				// trigger callback
				if ($.isFunction(callbackSuccess))
					callbackSuccess(response);
				
			}, callbackError);
		}
	}
	
})();
