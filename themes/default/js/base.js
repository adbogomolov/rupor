// jQuery.Easing
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,a,c,b,d){return jQuery.easing[jQuery.easing.def](e,a,c,b,d)},easeInQuad:function(e,a,c,b,d){return b*(a/=d)*a+c},easeOutQuad:function(e,a,c,b,d){return-b*(a/=d)*(a-2)+c},easeInOutQuad:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a+c:-b/2*(--a*(a-2)-1)+c},easeInCubic:function(e,a,c,b,d){return b*(a/=d)*a*a+c},easeOutCubic:function(e,a,c,b,d){return b*((a=a/d-1)*a*a+1)+c},easeInOutCubic:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a+c:b/2*((a-=2)*a*a+2)+c},easeInQuart:function(e,a,c,b,d){return b*(a/=d)*a*a*a+c},easeOutQuart:function(e,a,c,b,d){return-b*((a=a/d-1)*a*a*a-1)+c},easeInOutQuart:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a+c:-b/2*((a-=2)*a*a*a-2)+c},easeInQuint:function(e,a,c,b,d){return b*(a/=d)*a*a*a*a+c},easeOutQuint:function(e,a,c,b,d){return b*((a=a/d-1)*a*a*a*a+1)+c},easeInOutQuint:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a*a+c:b/2*((a-=2)*a*a*a*a+2)+c},easeInSine:function(e,a,c,b,d){return-b*Math.cos(a/d*(Math.PI/2))+b+c},easeOutSine:function(e,a,c,b,d){return b*Math.sin(a/d*(Math.PI/2))+c},easeInOutSine:function(e,a,c,b,d){return-b/2*(Math.cos(Math.PI*a/d)-1)+c},easeInExpo:function(e,a,c,b,d){return 0==a?c:b*Math.pow(2,10*(a/d-1))+c},easeOutExpo:function(e,a,c,b,d){return a==d?c+b:b*(-Math.pow(2,-10*a/d)+1)+c},easeInOutExpo:function(e,a,c,b,d){return 0==a?c:a==d?c+b:1>(a/=d/2)?b/2*Math.pow(2,10*(a-1))+c:b/2*(-Math.pow(2,-10*--a)+2)+c},easeInCirc:function(e,a,c,b,d){return-b*(Math.sqrt(1-(a/=d)*a)-1)+c},easeOutCirc:function(e,a,c,b,d){return b*Math.sqrt(1-(a=a/d-1)*a)+c},easeInOutCirc:function(e,a,c,b,d){return 1>(a/=d/2)?-b/2*(Math.sqrt(1-a*a)-1)+c:b/2*(Math.sqrt(1-(a-=2)*a)+1)+c},easeInElastic:function(e,a,c,b,d){var e=1.70158,f=0,g=b;if(0==a)return c;if(1==(a/=d))return c+b;f||(f=0.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return-(g*Math.pow(2,10*(a-=1))*Math.sin((a*d-e)*2*Math.PI/f))+c},easeOutElastic:function(e,a,c,b,d){var e=1.70158,f=0,g=b;if(0==a)return c;if(1==(a/=d))return c+b;f||(f=0.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return g*Math.pow(2,-10*a)*Math.sin((a*d-e)*2*Math.PI/f)+b+c},easeInOutElastic:function(e,a,c,b,d){var e=1.70158,f=0,g=b;if(0==a)return c;if(2==(a/=d/2))return c+b;f||(f=d*0.3*1.5);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return 1>a?-0.5*g*Math.pow(2,10*(a-=1))*Math.sin((a*d-e)*2*Math.PI/f)+c:0.5*g*Math.pow(2,-10*(a-=1))*Math.sin((a*d-e)*2*Math.PI/f)+b+c},easeInBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return b*(a/=d)*a*((f+1)*a-f)+c},easeOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return b*((a=a/d-1)*a*((f+1)*a+f)+1)+c},easeInOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return 1>(a/=d/2)?b/2*a*a*(((f*=1.525)+1)*a-f)+c:b/2*((a-=2)*a*(((f*=1.525)+1)*a+f)+2)+c},easeInBounce:function(e,a,c,b,d){return b-jQuery.easing.easeOutBounce(e,d-a,0,b,d)+c},easeOutBounce:function(e,a,c,b,d){return(a/=d)<1/2.75?b*7.5625*a*a+c:a<2/2.75?b*(7.5625*(a-=1.5/2.75)*a+0.75)+c:a<2.5/2.75?b*(7.5625*(a-=2.25/2.75)*a+0.9375)+c:b*(7.5625*(a-=2.625/2.75)*a+0.984375)+c},easeInOutBounce:function(e,a,c,b,d){return a<d/2?0.5*jQuery.easing.easeInBounce(e,2*a,0,b,d)+c:0.5*jQuery.easing.easeOutBounce(e,2*a-d,0,b,d)+0.5*b+c}});jQuery.extend(jQuery.easing,{easeIn:function(e,a,c,b,d){return jQuery.easing.easeInQuad(e,a,c,b,d)},easeOut:function(e,a,c,b,d){return jQuery.easing.easeOutQuad(e,a,c,b,d)},easeInOut:function(e,a,c,b,d){return jQuery.easing.easeInOutQuad(e,a,c,b,d)},expoin:function(e,a,c,b,d){return jQuery.easing.easeInExpo(e,a,c,b,d)},expoout:function(e,a,c,b,d){return jQuery.easing.easeOutExpo(e,a,c,b,d)},expoinout:function(e,a,c,b,d){return jQuery.easing.easeInOutExpo(e,a,c,b,d)},bouncein:function(e,a,c,b,d){return jQuery.easing.easeInBounce(e,a,c,b,d)},bounceout:function(e,a,c,b,d){return jQuery.easing.easeOutBounce(e,a,c,b,d)},bounceinout:function(e,a,c,b,d){return jQuery.easing.easeInOutBounce(e,a,c,b,d)},elasin:function(e,a,c,b,d){return jQuery.easing.easeInElastic(e,a,c,b,d)},elasout:function(e,a,c,b,d){return jQuery.easing.easeOutElastic(e,a,c,b,d)},elasinout:function(e,a,c,b,d){return jQuery.easing.easeInOutElastic(e,a,c,b,d)},backin:function(e,a,c,b,d){return jQuery.easing.easeInBack(e,a,c,b,d)},backout:function(e,a,c,b,d){return jQuery.easing.easeOutBack(e,a,c,b,d)},backinout:function(e,a,c,b,d){return jQuery.easing.easeInOutBack(e,a,c,b,d)}});
// jQuery Color Animations v2.0.0
(function(e,t){function h(e,t,n){var r=u[t.type]||{};if(e==null){return n||!t.def?null:t.def}e=r.floor?~~e:parseFloat(e);if(isNaN(e)){return t.def}if(r.mod){return(e+r.mod)%r.mod}return 0>e?0:r.max<e?r.max:e}function p(t){var n=s(),r=n._rgba=[];t=t.toLowerCase();c(i,function(e,i){var s,u=i.re.exec(t),a=u&&i.parse(u),f=i.space||"rgba";if(a){s=n[f](a);n[o[f].cache]=s[o[f].cache];r=n._rgba=s._rgba;return false}});if(r.length){if(r.join()==="0,0,0,0"){e.extend(r,l.transparent)}return n}return l[t]}function d(e,t,n){n=(n+1)%1;if(n*6<1){return e+(t-e)*n*6}if(n*2<1){return t}if(n*3<2){return e+(t-e)*(2/3-n)*6}return e}var n="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor".split(" "),r=/^([\-+])=\s*(\d+\.?\d*)/,i=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1]*2.55,e[2]*2.55,e[3]*2.55,e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],s=e.Color=function(t,n,r,i){return new e.Color.fn.parse(t,n,r,i)},o={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},u={"byte":{floor:true,max:255},percent:{max:1},degrees:{mod:360,floor:true}},a=s.support={},f=e("<p>")[0],l,c=e.each;f.style.cssText="background-color:rgba(1,1,1,.5)";a.rgba=f.style.backgroundColor.indexOf("rgba")>-1;c(o,function(e,t){t.cache="_"+e;t.props.alpha={idx:3,type:"percent",def:1}});s.fn=e.extend(s.prototype,{parse:function(n,r,i,u){if(n===t){this._rgba=[null,null,null,null];return this}if(n.jquery||n.nodeType){n=e(n).css(r);r=t}var a=this,f=e.type(n),d=this._rgba=[];if(r!==t){n=[n,r,i,u];f="array"}if(f==="string"){return this.parse(p(n)||l._default)}if(f==="array"){c(o.rgba.props,function(e,t){d[t.idx]=h(n[t.idx],t)});return this}if(f==="object"){if(n instanceof s){c(o,function(e,t){if(n[t.cache]){a[t.cache]=n[t.cache].slice()}})}else{c(o,function(e,t){var r=t.cache;c(t.props,function(e,i){if(!a[r]&&t.to){if(e==="alpha"||n[e]==null){return}a[r]=t.to(a._rgba)}a[r][i.idx]=h(n[e],i,true)});if(a[r]&&$.inArray(null,a[r].slice(0,3))<0){a[r][3]=1;if(t.from){a._rgba=t.from(a[r])}}})}return this}},is:function(e){var t=s(e),n=true,r=this;c(o,function(e,i){var s,o=t[i.cache];if(o){s=r[i.cache]||i.to&&i.to(r._rgba)||[];c(i.props,function(e,t){if(o[t.idx]!=null){n=o[t.idx]===s[t.idx];return n}})}return n});return n},_space:function(){var e=[],t=this;c(o,function(n,r){if(t[r.cache]){e.push(n)}});return e.pop()},transition:function(e,t){var n=s(e),r=n._space(),i=o[r],a=this.alpha()===0?s("transparent"):this,f=a[i.cache]||i.to(a._rgba),l=f.slice();n=n[i.cache];c(i.props,function(e,r){var i=r.idx,s=f[i],o=n[i],a=u[r.type]||{};if(o===null){return}if(s===null){l[i]=o}else{if(a.mod){if(o-s>a.mod/2){s+=a.mod}else if(s-o>a.mod/2){s-=a.mod}}l[i]=h((o-s)*t+s,r)}});return this[r](l)},blend:function(t){if(this._rgba[3]===1){return this}var n=this._rgba.slice(),r=n.pop(),i=s(t)._rgba;return s(e.map(n,function(e,t){return(1-r)*i[t]+r*e}))},toRgbaString:function(){var t="rgba(",n=e.map(this._rgba,function(e,t){return e==null?t>2?1:0:e});if(n[3]===1){n.pop();t="rgb("}return t+n.join()+")"},toHslaString:function(){var t="hsla(",n=e.map(this.hsla(),function(e,t){if(e==null){e=t>2?1:0}if(t&&t<3){e=Math.round(e*100)+"%"}return e});if(n[3]===1){n.pop();t="hsl("}return t+n.join()+")"},toHexString:function(t){var n=this._rgba.slice(),r=n.pop();if(t){n.push(~~(r*255))}return"#"+e.map(n,function(e){e=(e||0).toString(16);return e.length===1?"0"+e:e}).join("")},toString:function(){return this._rgba[3]===0?"transparent":this.toRgbaString()}});s.fn.parse.prototype=s.fn;o.hsla.to=function(e){if(e[0]==null||e[1]==null||e[2]==null){return[null,null,null,e[3]]}var t=e[0]/255,n=e[1]/255,r=e[2]/255,i=e[3],s=Math.max(t,n,r),o=Math.min(t,n,r),u=s-o,a=s+o,f=a*.5,l,c;if(o===s){l=0}else if(t===s){l=60*(n-r)/u+360}else if(n===s){l=60*(r-t)/u+120}else{l=60*(t-n)/u+240}if(f===0||f===1){c=f}else if(f<=.5){c=u/a}else{c=u/(2-a)}return[Math.round(l)%360,c,f,i==null?1:i]};o.hsla.from=function(e){if(e[0]==null||e[1]==null||e[2]==null){return[null,null,null,e[3]]}var t=e[0]/360,n=e[1],r=e[2],i=e[3],s=r<=.5?r*(1+n):r+n-r*n,o=2*r-s;return[Math.round(d(o,s,t+1/3)*255),Math.round(d(o,s,t)*255),Math.round(d(o,s,t-1/3)*255),i]};c(o,function(n,i){var o=i.props,u=i.cache,a=i.to,f=i.from;s.fn[n]=function(n){if(a&&!this[u]){this[u]=a(this._rgba)}if(n===t){return this[u].slice()}var r,i=e.type(n),l=i==="array"||i==="object"?n:arguments,p=this[u].slice();c(o,function(e,t){var n=l[i==="object"?e:t.idx];if(n==null){n=p[t.idx]}p[t.idx]=h(n,t)});if(f){r=s(f(p));r[u]=p;return r}else{return s(p)}};c(o,function(t,i){if(s.fn[t]){return}s.fn[t]=function(s){var o=e.type(s),u=t==="alpha"?this._hsla?"hsla":"rgba":n,a=this[u](),f=a[i.idx],l;if(o==="undefined"){return f}if(o==="function"){s=s.call(this,f);o=e.type(s)}if(s==null&&i.empty){return this}if(o==="string"){l=r.exec(s);if(l){s=f+parseFloat(l[2])*(l[1]==="+"?1:-1)}}a[i.idx]=s;return this[u](a)}})});c(n,function(t,n){e.cssHooks[n]={set:function(t,r){var i,o,u="";if(e.type(r)!=="string"||(i=p(r))){r=s(i||r);if(!a.rgba&&r._rgba[3]!==1){o=n==="backgroundColor"?t.parentNode:t;while((u===""||u==="transparent")&&o&&o.style){try{u=e.css(o,"backgroundColor");o=o.parentNode}catch(f){}}r=r.blend(u&&u!=="transparent"?u:"_default")}r=r.toRgbaString()}try{t.style[n]=r}catch(l){}}};e.fx.step[n]=function(t){if(!t.colorInit){t.start=s(t.elem,n);t.end=s(t.end);t.colorInit=true}e.cssHooks[n].set(t.elem,t.start.transition(t.end,t.pos))}});e.cssHooks.borderColor={expand:function(e){var t={};c(["Top","Right","Bottom","Left"],function(n,r){t["border"+r+"Color"]=e});return t}};l=e.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}})(jQuery);
// jQuery.Fancybox 2.1.4
(function(e,t,n,r){"use strict";var i=n(e),s=n(t),o=n.fancybox=function(){o.open.apply(this,arguments)},u=navigator.userAgent.match(/msie/),a=null,f=t.createTouch!==r,l=function(e){return e&&e.hasOwnProperty&&e instanceof n},c=function(e){return e&&n.type(e)==="string"},h=function(e){return c(e)&&e.indexOf("%")>0},p=function(e){return e&&!(e.style.overflow&&e.style.overflow==="hidden")&&(e.clientWidth&&e.scrollWidth>e.clientWidth||e.clientHeight&&e.scrollHeight>e.clientHeight)},d=function(e,t){var n=parseInt(e,10)||0;if(t&&h(e)){n=o.getViewport()[t]/100*n}return Math.ceil(n)},v=function(e,t){return d(e,t)+"px"};n.extend(o,{version:"2.1.4",defaults:{padding:15,margin:20,width:800,height:600,minWidth:100,minHeight:100,maxWidth:9999,maxHeight:9999,autoSize:true,autoHeight:false,autoWidth:false,autoResize:true,autoCenter:!f,fitToView:true,aspectRatio:false,topRatio:.5,leftRatio:.5,scrolling:"auto",wrapCSS:"",arrows:true,closeBtn:true,closeClick:false,nextClick:false,mouseWheel:true,autoPlay:false,playSpeed:3e3,preload:3,modal:false,loop:true,ajax:{dataType:"html",headers:{"X-fancyBox":true}},iframe:{scrolling:"auto",preload:true},swf:{wmode:"transparent",allowfullscreen:"true",allowscriptaccess:"always"},keys:{next:{13:"left",34:"up",39:"left",40:"up"},prev:{8:"right",33:"down",37:"right",38:"down"},close:[27],play:[32],toggle:[70]},direction:{next:"left",prev:"right"},scrollOutside:true,index:0,type:null,href:null,content:null,title:null,tpl:{wrap:'<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',image:'<img class="fancybox-image" src="{href}" alt="" />',iframe:'<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen'+(u?' allowtransparency="true"':"")+"></iframe>",error:'<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',closeBtn:'<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',next:'<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',prev:'<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'},openEffect:"fade",openSpeed:250,openEasing:"swing",openOpacity:true,openMethod:"zoomIn",closeEffect:"fade",closeSpeed:250,closeEasing:"swing",closeOpacity:true,closeMethod:"zoomOut",nextEffect:"elastic",nextSpeed:250,nextEasing:"swing",nextMethod:"changeIn",prevEffect:"elastic",prevSpeed:250,prevEasing:"swing",prevMethod:"changeOut",helpers:{overlay:true,title:true},onCancel:n.noop,beforeLoad:n.noop,afterLoad:n.noop,beforeShow:n.noop,afterShow:n.noop,beforeChange:n.noop,beforeClose:n.noop,afterClose:n.noop},group:{},opts:{},previous:null,coming:null,current:null,isActive:false,isOpen:false,isOpened:false,wrap:null,skin:null,outer:null,inner:null,player:{timer:null,isActive:false},ajaxLoad:null,imgPreload:null,transitions:{},helpers:{},open:function(e,t){if(!e){return}if(!n.isPlainObject(t)){t={}}if(false===o.close(true)){return}if(!n.isArray(e)){e=l(e)?n(e).get():[e]}n.each(e,function(i,s){var u={},a,f,h,p,d,v,m;if(n.type(s)==="object"){if(s.nodeType){s=n(s)}if(l(s)){u={href:s.data("fancybox-href")||s.attr("href"),title:s.data("fancybox-title")||s.attr("title"),isDom:true,element:s};if(n.metadata){n.extend(true,u,s.metadata())}}else{u=s}}a=t.href||u.href||(c(s)?s:null);f=t.title!==r?t.title:u.title||"";h=t.content||u.content;p=h?"html":t.type||u.type;if(!p&&u.isDom){p=s.data("fancybox-type");if(!p){d=s.prop("class").match(/fancybox\.(\w+)/);p=d?d[1]:null}}if(c(a)){if(!p){if(o.isImage(a)){p="image"}else if(o.isSWF(a)){p="swf"}else if(a.charAt(0)==="#"){p="inline"}else if(c(s)){p="html";h=s}}if(p==="ajax"){v=a.split(/\s+/,2);a=v.shift();m=v.shift()}}if(!h){if(p==="inline"){if(a){h=n(c(a)?a.replace(/.*(?=#[^\s]+$)/,""):a)}else if(u.isDom){h=s}}else if(p==="html"){h=a}else if(!p&&!a&&u.isDom){p="inline";h=s}}n.extend(u,{href:a,type:p,content:h,title:f,selector:m});e[i]=u});o.opts=n.extend(true,{},o.defaults,t);if(t.keys!==r){o.opts.keys=t.keys?n.extend({},o.defaults.keys,t.keys):false}o.group=e;return o._start(o.opts.index)},cancel:function(){var e=o.coming;if(!e||false===o.trigger("onCancel")){return}o.hideLoading();if(o.ajaxLoad){o.ajaxLoad.abort()}o.ajaxLoad=null;if(o.imgPreload){o.imgPreload.onload=o.imgPreload.onerror=null}if(e.wrap){e.wrap.stop(true,true).trigger("onReset").remove()}o.coming=null;if(!o.current){o._afterZoomOut(e)}},close:function(e){o.cancel();if(false===o.trigger("beforeClose")){return}o.unbindEvents();if(!o.isActive){return}if(!o.isOpen||e===true){n(".fancybox-wrap").stop(true).trigger("onReset").remove();o._afterZoomOut()}else{o.isOpen=o.isOpened=false;o.isClosing=true;n(".fancybox-item, .fancybox-nav").remove();o.wrap.stop(true,true).removeClass("fancybox-opened");o.transitions[o.current.closeMethod]()}},play:function(e){var t=function(){clearTimeout(o.player.timer)},r=function(){t();if(o.current&&o.player.isActive){o.player.timer=setTimeout(o.next,o.current.playSpeed)}},i=function(){t();n("body").unbind(".player");o.player.isActive=false;o.trigger("onPlayEnd")},s=function(){if(o.current&&(o.current.loop||o.current.index<o.group.length-1)){o.player.isActive=true;n("body").bind({"afterShow.player onUpdate.player":r,"onCancel.player beforeClose.player":i,"beforeLoad.player":t});r();o.trigger("onPlayStart")}};if(e===true||!o.player.isActive&&e!==false){s()}else{i()}},next:function(e){var t=o.current;if(t){if(!c(e)){e=t.direction.next}o.jumpto(t.index+1,e,"next")}},prev:function(e){var t=o.current;if(t){if(!c(e)){e=t.direction.prev}o.jumpto(t.index-1,e,"prev")}},jumpto:function(e,t,n){var i=o.current;if(!i){return}e=d(e);o.direction=t||i.direction[e>=i.index?"next":"prev"];o.router=n||"jumpto";if(i.loop){if(e<0){e=i.group.length+e%i.group.length}e=e%i.group.length}if(i.group[e]!==r){o.cancel();o._start(e)}},reposition:function(e,t){var r=o.current,i=r?r.wrap:null,s;if(i){s=o._getPosition(t);if(e&&e.type==="scroll"){delete s.position;i.stop(true,true).animate(s,200)}else{i.css(s);r.pos=n.extend({},r.dim,s)}}},update:function(e){var t=e&&e.type,n=!t||t==="orientationchange";if(n){clearTimeout(a);a=null}if(!o.isOpen||a){return}a=setTimeout(function(){var r=o.current;if(!r||o.isClosing){return}o.wrap.removeClass("fancybox-tmp");if(n||t==="load"||t==="resize"&&r.autoResize){o._setDimension()}if(!(t==="scroll"&&r.canShrink)){o.reposition(e)}o.trigger("onUpdate");a=null},n&&!f?0:300)},toggle:function(e){if(o.isOpen){o.current.fitToView=n.type(e)==="boolean"?e:!o.current.fitToView;if(f){o.wrap.removeAttr("style").addClass("fancybox-tmp");o.trigger("onUpdate")}o.update()}},hideLoading:function(){s.unbind(".loading");n("#fancybox-loading").remove()},showLoading:function(){var e,t;o.hideLoading();e=n('<div id="fancybox-loading"><div></div></div>').click(o.cancel).appendTo("body");s.bind("keydown.loading",function(e){if((e.which||e.keyCode)===27){e.preventDefault();o.cancel()}});if(!o.defaults.fixed){t=o.getViewport();e.css({position:"absolute",top:t.h*.5+t.y,left:t.w*.5+t.x})}},getViewport:function(){var t=o.current&&o.current.locked||false,n={x:i.scrollLeft(),y:i.scrollTop()};if(t){n.w=t[0].clientWidth;n.h=t[0].clientHeight}else{n.w=f&&e.innerWidth?e.innerWidth:i.width();n.h=f&&e.innerHeight?e.innerHeight:i.height()}return n},unbindEvents:function(){if(o.wrap&&l(o.wrap)){o.wrap.unbind(".fb")}s.unbind(".fb");i.unbind(".fb")},bindEvents:function(){var e=o.current,t;if(!e){return}i.bind("orientationchange.fb"+(f?"":" resize.fb")+(e.autoCenter&&!e.locked?" scroll.fb":""),o.update);t=e.keys;if(t){s.bind("keydown.fb",function(i){var s=i.which||i.keyCode,u=i.target||i.srcElement;if(s===27&&o.coming){return false}if(!i.ctrlKey&&!i.altKey&&!i.shiftKey&&!i.metaKey&&!(u&&(u.type||n(u).is("[contenteditable]")))){n.each(t,function(t,u){if(e.group.length>1&&u[s]!==r){o[t](u[s]);i.preventDefault();return false}if(n.inArray(s,u)>-1){o[t]();i.preventDefault();return false}})}})}if(n.fn.mousewheel&&e.mouseWheel){o.wrap.bind("mousewheel.fb",function(t,r,i,s){var u=t.target||null,a=n(u),f=false;while(a.length){if(f||a.is(".fancybox-skin")||a.is(".fancybox-wrap")){break}f=p(a[0]);a=n(a).parent()}if(r!==0&&!f){if(o.group.length>1&&!e.canShrink){if(s>0||i>0){o.prev(s>0?"down":"left")}else if(s<0||i<0){o.next(s<0?"up":"right")}t.preventDefault()}}})}},trigger:function(e,t){var r,i=t||o.coming||o.current;if(!i){return}if(n.isFunction(i[e])){r=i[e].apply(i,Array.prototype.slice.call(arguments,1))}if(r===false){return false}if(i.helpers){n.each(i.helpers,function(t,r){if(r&&o.helpers[t]&&n.isFunction(o.helpers[t][e])){r=n.extend(true,{},o.helpers[t].defaults,r);o.helpers[t][e](r,i)}})}n.event.trigger(e+".fb")},isImage:function(e){return c(e)&&e.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp)((\?|#).*)?$)/i)},isSWF:function(e){return c(e)&&e.match(/\.(swf)((\?|#).*)?$/i)},_start:function(e){var t={},r,i,s,u,a;e=d(e);r=o.group[e]||null;if(!r){return false}t=n.extend(true,{},o.opts,r);u=t.margin;a=t.padding;if(n.type(u)==="number"){t.margin=[u,u,u,u]}if(n.type(a)==="number"){t.padding=[a,a,a,a]}if(t.modal){n.extend(true,t,{closeBtn:false,closeClick:false,nextClick:false,arrows:false,mouseWheel:false,keys:null,helpers:{overlay:{closeClick:false}}})}if(t.autoSize){t.autoWidth=t.autoHeight=true}if(t.width==="auto"){t.autoWidth=true}if(t.height==="auto"){t.autoHeight=true}t.group=o.group;t.index=e;o.coming=t;if(false===o.trigger("beforeLoad")){o.coming=null;return}s=t.type;i=t.href;if(!s){o.coming=null;if(o.current&&o.router&&o.router!=="jumpto"){o.current.index=e;return o[o.router](o.direction)}return false}o.isActive=true;if(s==="image"||s==="swf"){t.autoHeight=t.autoWidth=false;t.scrolling="visible"}if(s==="image"){t.aspectRatio=true}if(s==="iframe"&&f){t.scrolling="scroll"}t.wrap=n(t.tpl.wrap).addClass("fancybox-"+(f?"mobile":"desktop")+" fancybox-type-"+s+" fancybox-tmp "+t.wrapCSS).appendTo(t.parent||"body");n.extend(t,{skin:n(".fancybox-skin",t.wrap),outer:n(".fancybox-outer",t.wrap),inner:n(".fancybox-inner",t.wrap)});n.each(["Top","Right","Bottom","Left"],function(e,n){t.skin.css("padding"+n,v(t.padding[e]))});o.trigger("onReady");if(s==="inline"||s==="html"){if(!t.content||!t.content.length){return o._error("content")}}else if(!i){return o._error("href")}if(s==="image"){o._loadImage()}else if(s==="ajax"){o._loadAjax()}else if(s==="iframe"){o._loadIframe()}else{o._afterLoad()}},_error:function(e){n.extend(o.coming,{type:"html",autoWidth:true,autoHeight:true,minWidth:0,minHeight:0,scrolling:"no",hasError:e,content:o.coming.tpl.error});o._afterLoad()},_loadImage:function(){var e=o.imgPreload=new Image;e.onload=function(){this.onload=this.onerror=null;o.coming.width=this.width;o.coming.height=this.height;o._afterLoad()};e.onerror=function(){this.onload=this.onerror=null;o._error("image")};e.src=o.coming.href;if(e.complete!==true){o.showLoading()}},_loadAjax:function(){var e=o.coming;o.showLoading();o.ajaxLoad=n.ajax(n.extend({},e.ajax,{url:e.href,error:function(e,t){if(o.coming&&t!=="abort"){o._error("ajax",e)}else{o.hideLoading()}},success:function(t,n){if(n==="success"){e.content=t;o._afterLoad()}}}))},_loadIframe:function(){var e=o.coming,t=n(e.tpl.iframe.replace(/\{rnd\}/g,(new Date).getTime())).attr("scrolling",f?"auto":e.iframe.scrolling).attr("src",e.href);n(e.wrap).bind("onReset",function(){try{n(this).find("iframe").hide().attr("src","//about:blank").end().empty()}catch(e){}});if(e.iframe.preload){o.showLoading();t.one("load",function(){n(this).data("ready",1);if(!f){n(this).bind("load.fb",o.update)}n(this).parents(".fancybox-wrap").width("100%").removeClass("fancybox-tmp").show();o._afterLoad()})}e.content=t.appendTo(e.inner);if(!e.iframe.preload){o._afterLoad()}},_preloadImages:function(){var e=o.group,t=o.current,n=e.length,r=t.preload?Math.min(t.preload,n-1):0,i,s;for(s=1;s<=r;s+=1){i=e[(t.index+s)%n];if(i.type==="image"&&i.href){(new Image).src=i.href}}},_afterLoad:function(){var e=o.coming,t=o.current,r="fancybox-placeholder",i,s,u,a,f,c;o.hideLoading();if(!e||o.isActive===false){return}if(false===o.trigger("afterLoad",e,t)){e.wrap.stop(true).trigger("onReset").remove();o.coming=null;return}if(t){o.trigger("beforeChange",t);t.wrap.stop(true).removeClass("fancybox-opened").find(".fancybox-item, .fancybox-nav").remove()}o.unbindEvents();i=e;s=e.content;u=e.type;a=e.scrolling;n.extend(o,{wrap:i.wrap,skin:i.skin,outer:i.outer,inner:i.inner,current:i,previous:t});f=i.href;switch(u){case"inline":case"ajax":case"html":if(i.selector){s=n("<div>").html(s).find(i.selector)}else if(l(s)){if(!s.data(r)){s.data(r,n('<div class="'+r+'"></div>').insertAfter(s).hide())}s=s.show().detach();i.wrap.bind("onReset",function(){if(n(this).find(s).length){s.hide().replaceAll(s.data(r)).data(r,false)}})}break;case"image":s=i.tpl.image.replace("{href}",f);break;case"swf":s='<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="'+f+'"></param>';c="";n.each(i.swf,function(e,t){s+='<param name="'+e+'" value="'+t+'"></param>';c+=" "+e+'="'+t+'"'});s+='<embed src="'+f+'" type="application/x-shockwave-flash" width="100%" height="100%"'+c+"></embed></object>";break}if(!(l(s)&&s.parent().is(i.inner))){i.inner.append(s)}o.trigger("beforeShow");i.inner.css("overflow",a==="yes"?"scroll":a==="no"?"hidden":a);o._setDimension();o.reposition();o.isOpen=false;o.coming=null;o.bindEvents();if(!o.isOpened){n(".fancybox-wrap").not(i.wrap).stop(true).trigger("onReset").remove()}else if(t.prevMethod){o.transitions[t.prevMethod]()}o.transitions[o.isOpened?i.nextMethod:i.openMethod]();o._preloadImages()},_setDimension:function(){var e=o.getViewport(),t=0,r=false,i=false,s=o.wrap,u=o.skin,a=o.inner,f=o.current,l=f.width,c=f.height,p=f.minWidth,m=f.minHeight,g=f.maxWidth,y=f.maxHeight,b=f.scrolling,w=f.scrollOutside?f.scrollbarWidth:0,E=f.margin,S=d(E[1]+E[3]),x=d(E[0]+E[2]),T,N,C,k,L,A,O,M,_,D,P,H,B,j,I;s.add(u).add(a).width("auto").height("auto").removeClass("fancybox-tmp");T=d(u.outerWidth(true)-u.width());N=d(u.outerHeight(true)-u.height());C=S+T;k=x+N;L=h(l)?(e.w-C)*d(l)/100:l;A=h(c)?(e.h-k)*d(c)/100:c;if(f.type==="iframe"){j=f.content;if(f.autoHeight&&j.data("ready")===1){try{if(j[0].contentWindow.document.location){a.width(L).height(9999);I=j.contents().find("body");if(w){I.css("overflow-x","hidden")}A=I.height()}}catch(q){}}}else if(f.autoWidth||f.autoHeight){a.addClass("fancybox-tmp");if(!f.autoWidth){a.width(L)}if(!f.autoHeight){a.height(A)}if(f.autoWidth){L=a.width()}if(f.autoHeight){A=a.height()}a.removeClass("fancybox-tmp")}l=d(L);c=d(A);_=L/A;p=d(h(p)?d(p,"w")-C:p);g=d(h(g)?d(g,"w")-C:g);m=d(h(m)?d(m,"h")-k:m);y=d(h(y)?d(y,"h")-k:y);O=g;M=y;if(f.fitToView){g=Math.min(e.w-C,g);y=Math.min(e.h-k,y)}H=e.w-S;B=e.h-x;if(f.aspectRatio){if(l>g){l=g;c=d(l/_)}if(c>y){c=y;l=d(c*_)}if(l<p){l=p;c=d(l/_)}if(c<m){c=m;l=d(c*_)}}else{l=Math.max(p,Math.min(l,g));if(f.autoHeight&&f.type!=="iframe"){a.width(l);c=a.height()}c=Math.max(m,Math.min(c,y))}if(f.fitToView){a.width(l).height(c);s.width(l+T);D=s.width();P=s.height();if(f.aspectRatio){while((D>H||P>B)&&l>p&&c>m){if(t++>19){break}c=Math.max(m,Math.min(y,c-10));l=d(c*_);if(l<p){l=p;c=d(l/_)}if(l>g){l=g;c=d(l/_)}a.width(l).height(c);s.width(l+T);D=s.width();P=s.height()}}else{l=Math.max(p,Math.min(l,l-(D-H)));c=Math.max(m,Math.min(c,c-(P-B)))}}if(w&&b==="auto"&&c<A&&l+T+w<H){l+=w}a.width(l).height(c);s.width(l+T);D=s.width();P=s.height();r=(D>H||P>B)&&l>p&&c>m;i=f.aspectRatio?l<O&&c<M&&l<L&&c<A:(l<O||c<M)&&(l<L||c<A);n.extend(f,{dim:{width:v(D),height:v(P)},origWidth:L,origHeight:A,canShrink:r,canExpand:i,wPadding:T,hPadding:N,wrapSpace:P-u.outerHeight(true),skinSpace:u.height()-c});if(!j&&f.autoHeight&&c>m&&c<y&&!i){a.height("auto")}},_getPosition:function(e){var t=o.current,n=o.getViewport(),r=t.margin,i=o.wrap.width()+r[1]+r[3],s=o.wrap.height()+r[0]+r[2],u={position:"absolute",top:r[0],left:r[3]};if(t.autoCenter&&t.fixed&&!e&&s<=n.h&&i<=n.w){u.position="fixed"}else if(!t.locked){u.top+=n.y;u.left+=n.x}u.top=v(Math.max(u.top,u.top+(n.h-s)*t.topRatio));u.left=v(Math.max(u.left,u.left+(n.w-i)*t.leftRatio));return u},_afterZoomIn:function(){var e=o.current;if(!e){return}o.isOpen=o.isOpened=true;o.wrap.css("overflow","visible").addClass("fancybox-opened");o.update();if(e.closeClick||e.nextClick&&o.group.length>1){o.inner.css("cursor","pointer").bind("click.fb",function(t){if(!n(t.target).is("a")&&!n(t.target).parent().is("a")){t.preventDefault();o[e.closeClick?"close":"next"]()}})}if(e.closeBtn){n(e.tpl.closeBtn).appendTo(o.skin).bind("click.fb",function(e){e.preventDefault();o.close()})}if(e.arrows&&o.group.length>1){if(e.loop||e.index>0){n(e.tpl.prev).appendTo(o.outer).bind("click.fb",o.prev)}if(e.loop||e.index<o.group.length-1){n(e.tpl.next).appendTo(o.outer).bind("click.fb",o.next)}}o.trigger("afterShow");if(!e.loop&&e.index===e.group.length-1){o.play(false)}else if(o.opts.autoPlay&&!o.player.isActive){o.opts.autoPlay=false;o.play()}},_afterZoomOut:function(e){e=e||o.current;n(".fancybox-wrap").trigger("onReset").remove();n.extend(o,{group:{},opts:{},router:false,current:null,isActive:false,isOpened:false,isOpen:false,isClosing:false,wrap:null,skin:null,outer:null,inner:null});o.trigger("afterClose",e)}});o.transitions={getOrigPosition:function(){var e=o.current,t=e.element,n=e.orig,r={},i=50,s=50,u=e.hPadding,a=e.wPadding,f=o.getViewport();if(!n&&e.isDom&&t.is(":visible")){n=t.find("img:first");if(!n.length){n=t}}if(l(n)){r=n.offset();if(n.is("img")){i=n.outerWidth();s=n.outerHeight()}}else{r.top=f.y+(f.h-s)*e.topRatio;r.left=f.x+(f.w-i)*e.leftRatio}if(o.wrap.css("position")==="fixed"||e.locked){r.top-=f.y;r.left-=f.x}r={top:v(r.top-u*e.topRatio),left:v(r.left-a*e.leftRatio),width:v(i+a),height:v(s+u)};return r},step:function(e,t){var n,r,i,s=t.prop,u=o.current,a=u.wrapSpace,f=u.skinSpace;if(s==="width"||s==="height"){n=t.end===t.start?1:(e-t.start)/(t.end-t.start);if(o.isClosing){n=1-n}r=s==="width"?u.wPadding:u.hPadding;i=e-r;o.skin[s](d(s==="width"?i:i-a*n));o.inner[s](d(s==="width"?i:i-a*n-f*n))}},zoomIn:function(){var e=o.current,t=e.pos,r=e.openEffect,i=r==="elastic",s=n.extend({opacity:1},t);delete s.position;if(i){t=this.getOrigPosition();if(e.openOpacity){t.opacity=.1}}else if(r==="fade"){t.opacity=.1}o.wrap.css(t).animate(s,{duration:r==="none"?0:e.openSpeed,easing:e.openEasing,step:i?this.step:null,complete:o._afterZoomIn})},zoomOut:function(){var e=o.current,t=e.closeEffect,n=t==="elastic",r={opacity:.1};if(n){r=this.getOrigPosition();if(e.closeOpacity){r.opacity=.1}}o.wrap.animate(r,{duration:t==="none"?0:e.closeSpeed,easing:e.closeEasing,step:n?this.step:null,complete:o._afterZoomOut})},changeIn:function(){var e=o.current,t=e.nextEffect,n=e.pos,r={opacity:1},i=o.direction,s=200,u;n.opacity=.1;if(t==="elastic"){u=i==="down"||i==="up"?"top":"left";if(i==="down"||i==="right"){n[u]=v(d(n[u])-s);r[u]="+="+s+"px"}else{n[u]=v(d(n[u])+s);r[u]="-="+s+"px"}}if(t==="none"){o._afterZoomIn()}else{o.wrap.css(n).animate(r,{duration:e.nextSpeed,easing:e.nextEasing,complete:o._afterZoomIn})}},changeOut:function(){var e=o.previous,t=e.prevEffect,r={opacity:.1},i=o.direction,s=200;if(t==="elastic"){r[i==="down"||i==="up"?"top":"left"]=(i==="up"||i==="left"?"-":"+")+"="+s+"px"}e.wrap.animate(r,{duration:t==="none"?0:e.prevSpeed,easing:e.prevEasing,complete:function(){n(this).trigger("onReset").remove()}})}};o.helpers.overlay={defaults:{closeClick:true,speedOut:200,showEarly:true,css:{},locked:!f,fixed:true},overlay:null,fixed:false,create:function(e){e=n.extend({},this.defaults,e);if(this.overlay){this.close()}this.overlay=n('<div class="fancybox-overlay"></div>').appendTo("body");this.fixed=false;if(e.fixed&&o.defaults.fixed){this.overlay.addClass("fancybox-overlay-fixed");this.fixed=true}},open:function(e){var t=this;e=n.extend({},this.defaults,e);if(this.overlay){this.overlay.unbind(".overlay").width("auto").height("auto")}else{this.create(e)}if(!this.fixed){i.bind("resize.overlay",n.proxy(this.update,this));this.update()}if(e.closeClick){this.overlay.bind("click.overlay",function(e){if(n(e.target).hasClass("fancybox-overlay")){if(o.isActive){o.close()}else{t.close()}}})}this.overlay.css(e.css).show()},close:function(){n(".fancybox-overlay").remove();i.unbind("resize.overlay");this.overlay=null;if(this.margin!==false){n("body").css("margin-right",this.margin);this.margin=false}if(this.el){this.el.removeClass("fancybox-lock")}},update:function(){var e="100%",n;this.overlay.width(e).height("100%");if(u){n=Math.max(t.documentElement.offsetWidth,t.body.offsetWidth);if(s.width()>n){e=s.width()}}else if(s.width()>i.width()){e=s.width()}this.overlay.width(e).height(s.height())},onReady:function(e,r){n(".fancybox-overlay").stop(true,true);if(!this.overlay){this.margin=s.height()>i.height()||n("body").css("overflow-y")==="scroll"?n("body").css("margin-right"):false;this.el=t.all&&!t.querySelector?n("html"):n("body");this.create(e)}if(e.locked&&this.fixed){r.locked=this.overlay.append(r.wrap);r.fixed=false}if(e.showEarly===true){this.beforeShow.apply(this,arguments)}},beforeShow:function(e,t){if(t.locked){this.el.addClass("fancybox-lock");if(this.margin!==false){n("body").css("margin-right",d(this.margin)+t.scrollbarWidth)}}this.open(e)},onUpdate:function(){if(!this.fixed){this.update()}},afterClose:function(e){if(this.overlay&&!o.isActive){this.overlay.fadeOut(e.speedOut,n.proxy(this.close,this))}}};o.helpers.title={defaults:{type:"float",position:"bottom"},beforeShow:function(e){var t=o.current,r=t.title,i=e.type,s,a;if(n.isFunction(r)){r=r.call(t.element,t)}if(!c(r)||n.trim(r)===""){return}s=n('<div class="fancybox-title fancybox-title-'+i+'-wrap">'+r+"</div>");switch(i){case"inside":a=o.skin;break;case"outside":a=o.wrap;break;case"over":a=o.inner;break;default:a=o.skin;s.appendTo("body");if(u){s.width(s.width())}s.wrapInner('<span class="child"></span>');o.current.margin[2]+=Math.abs(d(s.css("margin-bottom")));break}s[e.position==="top"?"prependTo":"appendTo"](a)}};n.fn.fancybox=function(e){var t,r=n(this),i=this.selector||"",u=function(s){var u=n(this).blur(),a=t,f,l;if(!(s.ctrlKey||s.altKey||s.shiftKey||s.metaKey)&&!u.is(".fancybox-wrap")){f=e.groupAttr||"data-fancybox-group";l=u.attr(f);if(!l){f="rel";l=u.get(0)[f]}if(l&&l!==""&&l!=="nofollow"){u=i.length?n(i):r;u=u.filter("["+f+'="'+l+'"]');a=u.index(this)}e.index=a;if(o.open(u,e)!==false){s.preventDefault()}}};e=e||{};t=e.index||0;if(!i||e.live===false){r.unbind("click.fb-start").bind("click.fb-start",u)}else{s.undelegate(i,"click.fb-start").delegate(i+":not('.fancybox-item, .fancybox-nav')","click.fb-start",u)}this.filter("[data-fancybox-start=1]").trigger("click");return this};s.ready(function(){if(n.scrollbarWidth===r){n.scrollbarWidth=function(){var e=n('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),t=e.children(),r=t.innerWidth()-t.height(99).innerWidth();e.remove();return r}}if(n.support.fixedPosition===r){n.support.fixedPosition=function(){var e=n('<div style="position:fixed;top:20px;"></div>').appendTo("body"),t=e[0].offsetTop===20||e[0].offsetTop===15;e.remove();return t}()}n.extend(o.defaults,{scrollbarWidth:n.scrollbarWidth(),fixed:n.support.fixedPosition,parent:n("body")})})})(window,document,jQuery);
// jQuery JSON plugin 2.4.0
(function($){"use strict";var escape=/["\\\x00-\x1f\x7f-\x9f]/g,meta={"\b":"\\b","	":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},hasOwn=Object.prototype.hasOwnProperty;$.toJSON=typeof JSON==="object"&&JSON.stringify?JSON.stringify:function(e){if(e===null){return"null"}var t,n,r,i,s=$.type(e);if(s==="undefined"){return undefined}if(s==="number"||s==="boolean"){return String(e)}if(s==="string"){return $.quoteString(e)}if(typeof e.toJSON==="function"){return $.toJSON(e.toJSON())}if(s==="date"){var o=e.getUTCMonth()+1,u=e.getUTCDate(),a=e.getUTCFullYear(),f=e.getUTCHours(),l=e.getUTCMinutes(),c=e.getUTCSeconds(),h=e.getUTCMilliseconds();if(o<10){o="0"+o}if(u<10){u="0"+u}if(f<10){f="0"+f}if(l<10){l="0"+l}if(c<10){c="0"+c}if(h<100){h="0"+h}if(h<10){h="0"+h}return'"'+a+"-"+o+"-"+u+"T"+f+":"+l+":"+c+"."+h+'Z"'}t=[];if($.isArray(e)){for(n=0;n<e.length;n++){t.push($.toJSON(e[n])||"null")}return"["+t.join(",")+"]"}if(typeof e==="object"){for(n in e){if(hasOwn.call(e,n)){s=typeof n;if(s==="number"){r='"'+n+'"'}else if(s==="string"){r=$.quoteString(n)}else{continue}s=typeof e[n];if(s!=="function"&&s!=="undefined"){i=$.toJSON(e[n]);t.push(r+":"+i)}}}return"{"+t.join(",")+"}"}};$.evalJSON=typeof JSON==="object"&&JSON.parse?JSON.parse:function(str){return eval("("+str+")")};$.secureEvalJSON=typeof JSON==="object"&&JSON.parse?JSON.parse:function(str){var filtered=str.replace(/\\["\\\/bfnrtu]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"");if(/^[\],:{}\s]*$/.test(filtered)){return eval("("+str+")")}throw new SyntaxError("Error parsing JSON, source is not valid.")};$.quoteString=function(e){if(e.match(escape)){return'"'+e.replace(escape,function(e){var t=meta[e];if(typeof t==="string"){return t}t=e.charCodeAt();return"\\u00"+Math.floor(t/16).toString(16)+(t%16).toString(16)})+'"'}return'"'+e+'"'}})(jQuery);
// jQuery Cookie Plugin v1.3.1
(function(e){if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(jQuery)}})(function(e){function n(e){return e}function r(e){return decodeURIComponent(e.replace(t," "))}function i(e){if(e.indexOf('"')===0){e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\")}try{return s.json?JSON.parse(e):e}catch(t){}}var t=/\+/g;var s=e.cookie=function(t,o,u){if(o!==undefined){u=e.extend({},s.defaults,u);if(typeof u.expires==="number"){var a=u.expires,f=u.expires=new Date;f.setDate(f.getDate()+a)}o=s.json?JSON.stringify(o):String(o);return document.cookie=[s.raw?t:encodeURIComponent(t),"=",s.raw?o:encodeURIComponent(o),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join("")}var l=s.raw?n:r;var c=document.cookie.split("; ");var h=t?undefined:{};for(var p=0,d=c.length;p<d;p++){var v=c[p].split("=");var m=l(v.shift());var g=l(v.join("="));if(t&&t===m){h=i(g);break}if(!t){h[m]=i(g)}}return h};s.defaults={};e.removeCookie=function(t,n){if(e.cookie(t)!==undefined){e.cookie(t,"",e.extend({},n,{expires:-1}));return true}return false}});
// jQuery autocompete plugin

// переименовал $.fn.autocomplete на $.fn.auto_complete
/**
 * @fileOverview jquery-autocomplete, the jQuery Autocompleter
 * @author <a href="mailto:dylan@dyve.net">Dylan Verheul</a>
 * @version 2.4.4
 * @requires jQuery 1.6+
 * @license MIT | GPL | Apache 2.0, see LICENSE.txt
 * @see https://github.com/dyve/jquery-autocomplete
 */
(function($) {
    "use strict";

    /**
     * jQuery autocomplete plugin
     * @param {object|string} options
     * @returns (object} jQuery object
     */
    $.fn.auto_complete = function(options) {
        var url;
        if (arguments.length > 1) {
            url = options;
            options = arguments[1];
            options.url = url;
        } else if (typeof options === 'string') {
            url = options;
            options = { url: url };
        }
        var opts = $.extend({}, $.fn.auto_complete.defaults, options);
        return this.each(function() {
            var $this = $(this);
            $this.data('autocompleter', new $.Autocompleter(
                $this,
                $.meta ? $.extend({}, opts, $this.data()) : opts
            ));
        });
    };

    /**
     * Store default options
     * @type {object}
     */
    $.fn.auto_complete.defaults = {
        inputClass: 'acInput',
        loadingClass: 'acLoading',
        resultsClass: 'acResults',
        selectClass: 'acSelect',
        queryParamName: 'q',
        extraParams: {},
        remoteDataType: false,
        lineSeparator: '\n',
        cellSeparator: '|',
        minChars: 2,
        maxItemsToShow: 10,
        delay: 400,
        useCache: true,
        maxCacheLength: 10,
        matchSubset: true,
        matchCase: false,
        matchInside: true,
        mustMatch: false,
        selectFirst: false,
        selectOnly: false,
        showResult: null,
        preventDefaultReturn: 1,
        preventDefaultTab: 0,
        autoFill: false,
        filterResults: true,
        filter: true,
        sortResults: true,
        sortFunction: null,
        onItemSelect: null,
        onNoMatch: null,
        onFinish: null,
        matchStringConverter: null,
        beforeUseConverter: null,
        autoWidth: 'min-width',
        useDelimiter: false,
        delimiterChar: ',',
        delimiterKeyCode: 188,
        processData: null,
        onError: null,
        enabled: true
    };

    /**
     * Sanitize result
     * @param {Object} result
     * @returns {Object} object with members value (String) and data (Object)
     * @private
     */
    var sanitizeResult = function(result) {
        var value, data;
        var type = typeof result;
        if (type === 'string') {
            value = result;
            data = {};
        } else if ($.isArray(result)) {
            value = result[0];
            data = result.slice(1);
        } else if (type === 'object') {
            value = result.value;
            data = result.data;
        }
        value = String(value);
        if (typeof data !== 'object') {
            data = {};
        }
        return {
            value: value,
            data: data
        };
    };

    /**
     * Sanitize integer
     * @param {mixed} value
     * @param {Object} options
     * @returns {Number} integer
     * @private
     */
    var sanitizeInteger = function(value, stdValue, options) {
        var num = parseInt(value, 10);
        options = options || {};
        if (isNaN(num) || (options.min && num < options.min)) {
            num = stdValue;
        }
        return num;
    };

    /**
     * Create partial url for a name/value pair
     */
    var makeUrlParam = function(name, value) {
        return [name, encodeURIComponent(value)].join('=');
    };

    /**
     * Build an url
     * @param {string} url Base url
     * @param {object} [params] Dictionary of parameters
     */
    var makeUrl = function(url, params) {
        var urlAppend = [];
        $.each(params, function(index, value) {
            urlAppend.push(makeUrlParam(index, value));
        });
        if (urlAppend.length) {
            url += url.indexOf('?') === -1 ? '?' : '&';
            url += urlAppend.join('&');
        }
        return url;
    };

    /**
     * Default sort filter
     * @param {object} a
     * @param {object} b
     * @param {boolean} matchCase
     * @returns {number}
     */
    var sortValueAlpha = function(a, b, matchCase) {
        a = String(a.value);
        b = String(b.value);
        if (!matchCase) {
            a = a.toLowerCase();
            b = b.toLowerCase();
        }
        if (a > b) {
            return 1;
        }
        if (a < b) {
            return -1;
        }
        return 0;
    };

    /**
     * Parse data received in text format
     * @param {string} text Plain text input
     * @param {string} lineSeparator String that separates lines
     * @param {string} cellSeparator String that separates cells
     * @returns {array} Array of autocomplete data objects
     */
    var plainTextParser = function(text, lineSeparator, cellSeparator) {
        var results = [];
        var i, j, data, line, value, lines;
        // Be nice, fix linebreaks before splitting on lineSeparator
        lines = String(text).replace('\r\n', '\n').split(lineSeparator);
        for (i = 0; i < lines.length; i++) {
            line = lines[i].split(cellSeparator);
            data = [];
            for (j = 0; j < line.length; j++) {
                data.push(decodeURIComponent(line[j]));
            }
            value = data.shift();
            results.push({ value: value, data: data });
        }
        return results;
    };

    /**
     * Autocompleter class
     * @param {object} $elem jQuery object with one input tag
     * @param {object} options Settings
     * @constructor
     */
    $.Autocompleter = function($elem, options) {

        /**
         * Assert parameters
         */
        if (!$elem || !($elem instanceof $) || $elem.length !== 1 || $elem.get(0).tagName.toUpperCase() !== 'INPUT') {
            throw new Error('Invalid parameter for jquery.Autocompleter, jQuery object with one element with INPUT tag expected.');
        }

        /**
         * @constant Link to this instance
         * @type object
         * @private
         */
        var self = this;

        /**
         * @property {object} Options for this instance
         * @public
         */
        this.options = options;

        /**
         * @property object Cached data for this instance
         * @private
         */
        this.cacheData_ = {};

        /**
         * @property {number} Number of cached data items
         * @private
         */
        this.cacheLength_ = 0;

        /**
         * @property {string} Class name to mark selected item
         * @private
         */
        this.selectClass_ = 'jquery-autocomplete-selected-item';

        /**
         * @property {number} Handler to activation timeout
         * @private
         */
        this.keyTimeout_ = null;

        /**
         * @property {number} Handler to finish timeout
         * @private
         */
        this.finishTimeout_ = null;

        /**
         * @property {number} Last key pressed in the input field (store for behavior)
         * @private
         */
        this.lastKeyPressed_ = null;

        /**
         * @property {string} Last value processed by the autocompleter
         * @private
         */
        this.lastProcessedValue_ = null;

        /**
         * @property {string} Last value selected by the user
         * @private
         */
        this.lastSelectedValue_ = null;

        /**
         * @property {boolean} Is this autocompleter active (showing results)?
         * @see showResults
         * @private
         */
        this.active_ = false;

        /**
         * @property {boolean} Is this autocompleter allowed to finish on blur?
         * @private
         */
        this.finishOnBlur_ = true;

        /**
         * Sanitize options
         */
        this.options.minChars = sanitizeInteger(this.options.minChars, $.fn.auto_complete.defaults.minChars, { min: 0 });
        this.options.maxItemsToShow = sanitizeInteger(this.options.maxItemsToShow, $.fn.auto_complete.defaults.maxItemsToShow, { min: 0 });
        this.options.maxCacheLength = sanitizeInteger(this.options.maxCacheLength, $.fn.auto_complete.defaults.maxCacheLength, { min: 1 });
        this.options.delay = sanitizeInteger(this.options.delay, $.fn.auto_complete.defaults.delay, { min: 0 });
        if (this.options.preventDefaultReturn != 2) {
            this.options.preventDefaultReturn = this.options.preventDefaultReturn ? 1 : 0;
        }
        if (this.options.preventDefaultTab != 2) {
            this.options.preventDefaultTab = this.options.preventDefaultTab ? 1 : 0;
        }

        /**
         * Init DOM elements repository
         */
        this.dom = {};

        /**
         * Store the input element we're attached to in the repository
         */
        this.dom.$elem = $elem;

        /**
         * Switch off the native autocomplete and add the input class
         */
        this.dom.$elem.attr('autocomplete', 'off').addClass(this.options.inputClass);

        /**
         * Create DOM element to hold results, and force absolute position
         */
        this.dom.$results = $('<div></div>').hide().addClass(this.options.resultsClass).css({
            position: 'absolute'
        });
        $('body').append(this.dom.$results);

        /**
         * Attach keyboard monitoring to $elem
         */
        $elem.keydown(function(e) {
            self.lastKeyPressed_ = e.keyCode;
            switch(self.lastKeyPressed_) {

                case self.options.delimiterKeyCode: // comma = 188
                    if (self.options.useDelimiter && self.active_) {
                        self.selectCurrent();
                    }
                    break;

                // ignore navigational & special keys
                case 35: // end
                case 36: // home
                case 16: // shift
                case 17: // ctrl
                case 18: // alt
                case 37: // left
                case 39: // right
                    break;

                case 38: // up
                    e.preventDefault();
                    if (self.active_) {
                        self.focusPrev();
                    } else {
                        self.activate();
                    }
                    return false;

                case 40: // down
                    e.preventDefault();
                    if (self.active_) {
                        self.focusNext();
                    } else {
                        self.activate();
                    }
                    return false;

                case 9: // tab
                    if (self.active_) {
                        self.selectCurrent();
                        if (self.options.preventDefaultTab) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    if (self.options.preventDefaultTab === 2) {
                        e.preventDefault();
                        return false;
                    }
                break;

                case 13: // return
                    if (self.active_) {
                        self.selectCurrent();
                        if (self.options.preventDefaultReturn) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    if (self.options.preventDefaultReturn === 2) {
                        e.preventDefault();
                        return false;
                    }
                break;

                case 27: // escape
                    if (self.active_) {
                        e.preventDefault();
                        self.deactivate(true);
                        return false;
                    }
                break;

                default:
                    self.activate();

            }
        });

        /**
         * Attach paste event listener because paste may occur much later then keydown or even without a keydown at all
         */
        $elem.on('paste', function() {
            self.activate();
        });

        /**
         * Finish on blur event
         * Use a timeout because instant blur gives race conditions
         */
        var onBlurFunction = function() {
            self.deactivate(true);
        }
        $elem.blur(function() {
            if (self.finishOnBlur_) {
                self.finishTimeout_ = setTimeout(onBlurFunction, 200);
            }
        });
        /**
         * Catch a race condition on form submit
         */
        $elem.parents('form').on('submit', onBlurFunction);

    };

    /**
     * Position output DOM elements
     * @private
     */
    $.Autocompleter.prototype.position = function() {
        var offset = this.dom.$elem.offset();
        var height = this.dom.$results.outerHeight();
        var totalHeight = $(window).outerHeight();
        var inputBottom = offset.top + this.dom.$elem.outerHeight();
        var bottomIfDown = inputBottom + height;
        // Set autocomplete results at the bottom of input
        var position = {top: inputBottom, left: offset.left};
        if (bottomIfDown > totalHeight) {
            // Try to set autocomplete results at the top of input
            var topIfUp = offset.top - height;
            if (topIfUp >= 0) {
                position.top = topIfUp;
            }
        }
        this.dom.$results.css(position);
    };

    /**
     * Read from cache
     * @private
     */
    $.Autocompleter.prototype.cacheRead = function(filter) {
        var filterLength, searchLength, search, maxPos, pos;
        if (this.options.useCache) {
            filter = String(filter);
            filterLength = filter.length;
            if (this.options.matchSubset) {
                searchLength = 1;
            } else {
                searchLength = filterLength;
            }
            while (searchLength <= filterLength) {
                if (this.options.matchInside) {
                    maxPos = filterLength - searchLength;
                } else {
                    maxPos = 0;
                }
                pos = 0;
                while (pos <= maxPos) {
                    search = filter.substr(0, searchLength);
                    if (this.cacheData_[search] !== undefined) {
                        return this.cacheData_[search];
                    }
                    pos++;
                }
                searchLength++;
            }
        }
        return false;
    };

    /**
     * Write to cache
     * @private
     */
    $.Autocompleter.prototype.cacheWrite = function(filter, data) {
        if (this.options.useCache) {
            if (this.cacheLength_ >= this.options.maxCacheLength) {
                this.cacheFlush();
            }
            filter = String(filter);
            if (this.cacheData_[filter] !== undefined) {
                this.cacheLength_++;
            }
            this.cacheData_[filter] = data;
            return this.cacheData_[filter];
        }
        return false;
    };

    /**
     * Flush cache
     * @public
     */
    $.Autocompleter.prototype.cacheFlush = function() {
        this.cacheData_ = {};
        this.cacheLength_ = 0;
    };

    /**
     * Call hook
     * Note that all called hooks are passed the autocompleter object
     * @param {string} hook
     * @param data
     * @returns Result of called hook, false if hook is undefined
     */
    $.Autocompleter.prototype.callHook = function(hook, data) {
        var f = this.options[hook];
        if (f && $.isFunction(f)) {
            return f(data, this);
        }
        return false;
    };

    /**
     * Set timeout to activate autocompleter
     */
    $.Autocompleter.prototype.activate = function() {
        if (!this.options.enabled) return;
        var self = this;
        if (this.keyTimeout_) {
            clearTimeout(this.keyTimeout_);
        }
        this.keyTimeout_ = setTimeout(function() {
            self.activateNow();
        }, this.options.delay);
    };

    /**
     * Activate autocompleter immediately
     */
    $.Autocompleter.prototype.activateNow = function() {
        var value = this.beforeUseConverter(this.dom.$elem.val());
        if (value !== this.lastProcessedValue_ && value !== this.lastSelectedValue_) {
            this.fetchData(value);
        }
    };

    /**
     * Get autocomplete data for a given value
     * @param {string} value Value to base autocompletion on
     * @private
     */
    $.Autocompleter.prototype.fetchData = function(value) {
        var self = this;
        var processResults = function(results, filter) {
            if (self.options.processData) {
                results = self.options.processData(results);
            }
            self.showResults(self.filterResults(results, filter), filter);
        };
        this.lastProcessedValue_ = value;
        if (value.length < this.options.minChars) {
            processResults([], value);
        } else if (this.options.data) {
            processResults(this.options.data, value);
        } else {
            this.fetchRemoteData(value, function(remoteData) {
                processResults(remoteData, value);
            });
        }
    };

    /**
     * Get remote autocomplete data for a given value
     * @param {string} filter The filter to base remote data on
     * @param {function} callback The function to call after data retrieval
     * @private
     */
    $.Autocompleter.prototype.fetchRemoteData = function(filter, callback) {
        var data = this.cacheRead(filter);
        if (data) {
            callback(data);
        } else {
            var self = this;
            var dataType = self.options.remoteDataType === 'json' ? 'json' : 'text';
            var ajaxCallback = function(data) {
                var parsed = false;
                if (data !== false) {
                    parsed = self.parseRemoteData(data);
                    self.cacheWrite(filter, parsed);
                }
                self.dom.$elem.removeClass(self.options.loadingClass);
                callback(parsed);
            };
            this.dom.$elem.addClass(this.options.loadingClass);

            $.ajax({
                url: this.makeUrl(filter),
                success: ajaxCallback,
                error: function(jqXHR, textStatus, errorThrown) {
                    if($.isFunction(self.options.onError)) {
                        self.options.onError(jqXHR, textStatus, errorThrown);
                    } else {
                      ajaxCallback(false);
                    }
                },
                dataType: dataType
            });
        }
    };

    /**
     * Create or update an extra parameter for the remote request
     * @param {string} name Parameter name
     * @param {string} value Parameter value
     * @public
     */
    $.Autocompleter.prototype.setExtraParam = function(name, value) {
        var index = $.trim(String(name));
        if (index) {
            if (!this.options.extraParams) {
                this.options.extraParams = {};
            }
            if (this.options.extraParams[index] !== value) {
                this.options.extraParams[index] = value;
                this.cacheFlush();
            }
        }

        return this;
    };

    /**
     * Build the url for a remote request
     * If options.queryParamName === false, append query to url instead of using a GET parameter
     * @param {string} param The value parameter to pass to the backend
     * @returns {string} The finished url with parameters
     */
    $.Autocompleter.prototype.makeUrl = function(param) {
        var self = this;
        var url = this.options.url;
        var params = $.extend({}, this.options.extraParams);

        if (this.options.queryParamName === false) {
            url += encodeURIComponent(param);
        } else {
            params[this.options.queryParamName] = param;
        }

        return makeUrl(url, params);
    };

    /**
     * Parse data received from server
     * @param remoteData Data received from remote server
     * @returns {array} Parsed data
     */
    $.Autocompleter.prototype.parseRemoteData = function(remoteData) {
        var remoteDataType;
        var data = remoteData;
        if (this.options.remoteDataType === 'json') {
            remoteDataType = typeof(remoteData);
            switch (remoteDataType) {
                case 'object':
                    data = remoteData;
                    break;
                case 'string':
                    data = $.parseJSON(remoteData);
                    break;
                default:
                    throw new Error("Unexpected remote data type: " + remoteDataType);
            }
            return data;
        }
        return plainTextParser(data, this.options.lineSeparator, this.options.cellSeparator);
    };

    /**
     * Default filter for results
     * @param {Object} result
     * @param {String} filter
     * @returns {boolean} Include this result
     * @private
     */
    $.Autocompleter.prototype.defaultFilter = function(result, filter) {
        if (!result.value) {
            return false;
        }
        if (this.options.filterResults) {
            var pattern = this.matchStringConverter(filter);
            var testValue = this.matchStringConverter(result.value);
            if (!this.options.matchCase) {
                pattern = pattern.toLowerCase();
                testValue = testValue.toLowerCase();
            }
            var patternIndex = testValue.indexOf(pattern);
            if (this.options.matchInside) {
                return patternIndex > -1;
            } else {
                return patternIndex === 0;
            }
        }
        return true;
    };

    /**
     * Filter result
     * @param {Object} result
     * @param {String} filter
     * @returns {boolean} Include this result
     * @private
     */
    $.Autocompleter.prototype.filterResult = function(result, filter) {
        // No filter
        if (this.options.filter === false) {
            return true;
        }
        // Custom filter
        if ($.isFunction(this.options.filter)) {
            return this.options.filter(result, filter);
        }
        // Default filter
        return this.defaultFilter(result, filter);
    };

    /**
     * Filter results
     * @param results
     * @param filter
     */
    $.Autocompleter.prototype.filterResults = function(results, filter) {
        var filtered = [];
        var i, result;

        for (i = 0; i < results.length; i++) {
            result = sanitizeResult(results[i]);
            if (this.filterResult(result, filter)) {
                filtered.push(result);
            }
        }
        if (this.options.sortResults) {
            filtered = this.sortResults(filtered, filter);
        }
        if (this.options.maxItemsToShow > 0 && this.options.maxItemsToShow < filtered.length) {
            filtered.length = this.options.maxItemsToShow;
        }
        return filtered;
    };

    /**
     * Sort results
     * @param results
     * @param filter
     */
    $.Autocompleter.prototype.sortResults = function(results, filter) {
        var self = this;
        var sortFunction = this.options.sortFunction;
        if (!$.isFunction(sortFunction)) {
            sortFunction = function(a, b, f) {
                return sortValueAlpha(a, b, self.options.matchCase);
            };
        }
        results.sort(function(a, b) {
            return sortFunction(a, b, filter, self.options);
        });
        return results;
    };

    /**
     * Convert string before matching
     * @param s
     * @param a
     * @param b
     */
    $.Autocompleter.prototype.matchStringConverter = function(s, a, b) {
        var converter = this.options.matchStringConverter;
        if ($.isFunction(converter)) {
            s = converter(s, a, b);
        }
        return s;
    };

    /**
     * Convert string before use
     * @param {String} s
     */
    $.Autocompleter.prototype.beforeUseConverter = function(s) {
        s = this.getValue(s);
        var converter = this.options.beforeUseConverter;
        if ($.isFunction(converter)) {
            s = converter(s);
        }
        return s;
    };

    /**
     * Enable finish on blur event
     */
    $.Autocompleter.prototype.enableFinishOnBlur = function() {
        this.finishOnBlur_ = true;
    };

    /**
     * Disable finish on blur event
     */
    $.Autocompleter.prototype.disableFinishOnBlur = function() {
        this.finishOnBlur_ = false;
    };

    /**
     * Create a results item (LI element) from a result
     * @param result
     */
    $.Autocompleter.prototype.createItemFromResult = function(result) {
        var self = this;
        var $li = $('<li/>');
        $li.html(this.showResult(result.value, result.data));
        $li.data({value: result.value, data: result.data})
            .click(function() {
                self.selectItem($li);
            })
            .mousedown(self.disableFinishOnBlur)
            .mouseup(self.enableFinishOnBlur)
        ;
        return $li;
    };

    /**
     * Get all items from the results list
     * @param result
     */
    $.Autocompleter.prototype.getItems = function() {
        return $('>ul>li', this.dom.$results);
    };

    /**
     * Show all results
     * @param results
     * @param filter
     */
    $.Autocompleter.prototype.showResults = function(results, filter) {
        var numResults = results.length;
        var self = this;
        var $ul = $('<ul></ul>');
        var i, result, $li, autoWidth, first = false, $first = false;

        if (numResults) {
            for (i = 0; i < numResults; i++) {
                result = results[i];
                $li = this.createItemFromResult(result);
                $ul.append($li);
                if (first === false) {
                    first = String(result.value);
                    $first = $li;
                    $li.addClass(this.options.firstItemClass);
                }
                if (i === numResults - 1) {
                    $li.addClass(this.options.lastItemClass);
                }
            }

            this.dom.$results.html($ul).show();

            // Always recalculate position since window size or
            // input element location may have changed.
            this.position();
            if (this.options.autoWidth) {
                autoWidth = this.dom.$elem.outerWidth() - this.dom.$results.outerWidth() + this.dom.$results.width();
                this.dom.$results.css(this.options.autoWidth, autoWidth);
            }
            this.getItems().hover(
                function() { self.focusItem(this); },
                function() { /* void */ }
            );
            if (this.autoFill(first, filter) || this.options.selectFirst || (this.options.selectOnly && numResults === 1)) {
                this.focusItem($first);
            }
            this.active_ = true;
        } else {
            this.hideResults();
            this.active_ = false;
        }
    };

    $.Autocompleter.prototype.showResult = function(value, data) {
        if ($.isFunction(this.options.showResult)) {
            return this.options.showResult(value, data);
        } else {
            return $('<p></p>').text(value).html();
        }
    };

    $.Autocompleter.prototype.autoFill = function(value, filter) {
        var lcValue, lcFilter, valueLength, filterLength;
        if (this.options.autoFill && this.lastKeyPressed_ !== 8) {
            lcValue = String(value).toLowerCase();
            lcFilter = String(filter).toLowerCase();
            valueLength = value.length;
            filterLength = filter.length;
            if (lcValue.substr(0, filterLength) === lcFilter) {
                var d = this.getDelimiterOffsets();
                var pad = d.start ? ' ' : ''; // if there is a preceding delimiter
                this.setValue( pad + value );
                var start = filterLength + d.start + pad.length;
                var end = valueLength + d.start + pad.length;
                this.selectRange(start, end);
                return true;
            }
        }
        return false;
    };

    $.Autocompleter.prototype.focusNext = function() {
        this.focusMove(+1);
    };

    $.Autocompleter.prototype.focusPrev = function() {
        this.focusMove(-1);
    };

    $.Autocompleter.prototype.focusMove = function(modifier) {
        var $items = this.getItems();
        modifier = sanitizeInteger(modifier, 0);
        if (modifier) {
            for (var i = 0; i < $items.length; i++) {
                if ($($items[i]).hasClass(this.selectClass_)) {
                    this.focusItem(i + modifier);
                    return;
                }
            }
        }
        this.focusItem(0);
    };

    $.Autocompleter.prototype.focusItem = function(item) {
        var $item, $items = this.getItems();
        if ($items.length) {
            $items.removeClass(this.selectClass_).removeClass(this.options.selectClass);
            if (typeof item === 'number') {
                if (item < 0) {
                    item = 0;
                } else if (item >= $items.length) {
                    item = $items.length - 1;
                }
                $item = $($items[item]);
            } else {
                $item = $(item);
            }
            if ($item) {
                $item.addClass(this.selectClass_).addClass(this.options.selectClass);
            }
        }
    };

    $.Autocompleter.prototype.selectCurrent = function() {
        var $item = $('li.' + this.selectClass_, this.dom.$results);
        if ($item.length === 1) {
            this.selectItem($item);
        } else {
            this.deactivate(false);
        }
    };

    $.Autocompleter.prototype.selectItem = function($li) {
        var value = $li.data('value');
        var data = $li.data('data');
        var displayValue = this.displayValue(value, data);
        var processedDisplayValue = this.beforeUseConverter(displayValue);
        this.lastProcessedValue_ = processedDisplayValue;
        this.lastSelectedValue_ = processedDisplayValue;
        var d = this.getDelimiterOffsets();
        var delimiter = this.options.delimiterChar;
        var elem = this.dom.$elem;
        var extraCaretPos = 0;
        if ( this.options.useDelimiter ) {
            // if there is a preceding delimiter, add a space after the delimiter
            if ( elem.val().substring(d.start-1, d.start) == delimiter && delimiter != ' ' ) {
                displayValue = ' ' + displayValue;
            }
            // if there is not already a delimiter trailing this value, add it
            if ( elem.val().substring(d.end, d.end+1) != delimiter && this.lastKeyPressed_ != this.options.delimiterKeyCode ) {
                displayValue = displayValue + delimiter;
            } else {
                // move the cursor after the existing trailing delimiter
                extraCaretPos = 1;
            }
        }
        this.setValue(displayValue);
        this.setCaret(d.start + displayValue.length + extraCaretPos);
        this.callHook('onItemSelect', { value: value, data: data });
        this.deactivate(true);
        elem.focus();
    };

    $.Autocompleter.prototype.displayValue = function(value, data) {
        if ($.isFunction(this.options.displayValue)) {
            return this.options.displayValue(value, data);
        }
        return value;
    };

    $.Autocompleter.prototype.hideResults = function() {
        this.dom.$results.hide();
    };

    $.Autocompleter.prototype.deactivate = function(finish) {
        if (this.finishTimeout_) {
            clearTimeout(this.finishTimeout_);
        }
        if (this.keyTimeout_) {
            clearTimeout(this.keyTimeout_);
        }
        if (finish) {
            if (this.lastProcessedValue_ !== this.lastSelectedValue_) {
                if (this.options.mustMatch) {
                    this.setValue('');
                }
                this.callHook('onNoMatch');
            }
            if (this.active_) {
                this.callHook('onFinish');
            }
            this.lastKeyPressed_ = null;
            this.lastProcessedValue_ = null;
            this.lastSelectedValue_ = null;
            this.active_ = false;
        }
        this.hideResults();
    };

    $.Autocompleter.prototype.selectRange = function(start, end) {
        var input = this.dom.$elem.get(0);
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(start, end);
        } else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    };

    /**
     * Move caret to position
     * @param {Number} pos
     */
    $.Autocompleter.prototype.setCaret = function(pos) {
        this.selectRange(pos, pos);
    };

    /**
     * Get caret position
     */
    $.Autocompleter.prototype.getCaret = function() {
        var $elem = this.dom.$elem;
        var elem = $elem[0];
        var val, selection, range, start, end, stored_range;
        if (elem.createTextRange) { // IE
            selection = document.selection;
            if (elem.tagName.toLowerCase() != 'textarea') {
                val = $elem.val();
                range = selection.createRange().duplicate();
                range.moveEnd('character', val.length);
                if (range.text === '') {
                    start = val.length;
                } else {
                    start = val.lastIndexOf(range.text);
                }
                range = selection.createRange().duplicate();
                range.moveStart('character', -val.length);
                end = range.text.length;
            } else {
                range = selection.createRange();
                stored_range = range.duplicate();
                stored_range.moveToElementText(elem);
                stored_range.setEndPoint('EndToEnd', range);
                start = stored_range.text.length - range.text.length;
                end = start + range.text.length;
            }
        } else {
            start = $elem[0].selectionStart;
            end = $elem[0].selectionEnd;
        }
        return {
            start: start,
            end: end
        };
    };

    /**
     * Set the value that is currently being autocompleted
     * @param {String} value
     */
    $.Autocompleter.prototype.setValue = function(value) {
        if ( this.options.useDelimiter ) {
            // set the substring between the current delimiters
            var val = this.dom.$elem.val();
            var d = this.getDelimiterOffsets();
            var preVal = val.substring(0, d.start);
            var postVal = val.substring(d.end);
            value = preVal + value + postVal;
        }
        this.dom.$elem.val(value);
    };

    /**
     * Get the value currently being autocompleted
     * @param {String} value
     */
    $.Autocompleter.prototype.getValue = function(value) {
        if ( this.options.useDelimiter ) {
            var d = this.getDelimiterOffsets();
            return value.substring(d.start, d.end).trim();
        } else {
            return value;
        }
    };

    /**
     * Get the offsets of the value currently being autocompleted
     */
    $.Autocompleter.prototype.getDelimiterOffsets = function() {
        var val = this.dom.$elem.val();
        if ( this.options.useDelimiter ) {
            var preCaretVal = val.substring(0, this.getCaret().start);
            var start = preCaretVal.lastIndexOf(this.options.delimiterChar) + 1;
            var postCaretVal = val.substring(this.getCaret().start);
            var end = postCaretVal.indexOf(this.options.delimiterChar);
            if ( end == -1 ) end = val.length;
            end += this.getCaret().start;
        } else {
            start = 0;
            end = val.length;
        }
        return {
            start: start,
            end: end
        };
    };

})(jQuery);

// toolbox/toolbox.tooltip.js
(function(e){function n(t,n,r){var i=r.relative?t.position().top:t.offset().top,s=r.relative?t.position().left:t.offset().left,o=r.position[0];i-=n.outerHeight()-r.offset[0];s+=t.outerWidth()+r.offset[1];if(/iPad/i.test(navigator.userAgent)){i-=e(window).scrollTop()}var u=n.outerHeight()+t.outerHeight();if(o=="center"){i+=u/2}if(o=="bottom"){i+=u}o=r.position[1];var a=n.outerWidth()+t.outerWidth();if(o=="center"){s-=a/2}if(o=="left"){s-=a}return{top:i,left:s}}function r(r,i){var s=this,o=r.add(s),u,a=0,f=0,l=r.attr("title"),c=r.attr("data-tooltip"),h=t[i.effect],p,d=r.is(":input"),v=d&&r.is(":checkbox, :radio, select, :button, :submit"),m=r.attr("type"),g=i.events[m]||i.events[d?v?"widget":"input":"def"];if(!h){throw'Nonexistent effect "'+i.effect+'"'}g=g.split(/,\s*/);if(g.length!=2){throw"Tooltip: bad events configuration for "+m}r.on(g[0],function(e){clearTimeout(a);if(i.predelay){f=setTimeout(function(){s.show(e)},i.predelay)}else{s.show(e)}}).on(g[1],function(e){clearTimeout(f);if(i.delay){a=setTimeout(function(){s.hide(e)},i.delay)}else{s.hide(e)}});if(l&&i.cancelDefault){r.removeAttr("title");r.data("title",l)}e.extend(s,{show:function(t){if(!u){u=e(i.layout).addClass(i.tipClass).appendTo(document.body);if(c){u.html(e(c).show())}else if(i.tip){u.html(e(i.tip).eq(0))}else if(l){u.text(l)}else{if(r.next().length)u.html(r.next().show());else u.html(r.parent().next().show())}if(!u.length){throw"Cannot find tooltip for "+r}}if(s.isShown()){return s}u.addClass("tooltip-"+i.position.join("-"));u.stop(true,true);var d=n(r,u,i);if(i.tip){u.html(r.data("title"))}t=e.Event();t.type="onBeforeShow";o.trigger(t,[d]);if(t.isDefaultPrevented()){return s}d=n(r,u,i);u.css({position:"absolute",top:d.top,left:d.left});p=true;h[0].call(s,function(){t.type="onShow";p="full";o.trigger(t)});var v=i.events.tooltip.split(/,\s*/);if(!u.data("__set")){u.off(v[0]).on(v[0],function(){clearTimeout(a);clearTimeout(f)});if(v[1]&&!r.is("input:not(:checkbox, :radio), textarea")){u.off(v[1]).on(v[1],function(e){if(e.relatedTarget!=r[0]){r.trigger(g[1].split(" ")[0])}})}if(!i.tip)u.data("__set",true)}return s},hide:function(n){if(!u||!s.isShown()){return s}n=e.Event();n.type="onBeforeHide";o.trigger(n);if(n.isDefaultPrevented()){return}p=false;t[i.effect][1].call(s,function(){n.type="onHide";o.trigger(n)});return s},isShown:function(e){return e?p=="full":p},getConf:function(){return i},getTip:function(){return u},getTrigger:function(){return r}});e.each("onHide,onBeforeShow,onShow,onBeforeHide".split(","),function(t,n){if(e.isFunction(i[n])){e(s).on(n,i[n])}s[n]=function(t){if(t){e(s).on(n,t)}return s}})}e.tools=e.tools||{version:"@VERSION"};e.tools.tooltip={conf:{effect:"toggle",fadeOutSpeed:"fast",predelay:0,delay:30,opacity:1,tip:0,fadeIE:false,position:["top","center"],offset:[0,0],relative:false,cancelDefault:true,events:{def:"mouseenter,mouseleave",input:"focus,blur",widget:"focus mouseenter,blur mouseleave",tooltip:"mouseenter,mouseleave"},layout:"<div/>",tipClass:"tooltip"},addEffect:function(e,n,r){t[e]=[n,r]}};var t={toggle:[function(e){var t=this.getConf(),n=this.getTip(),r=t.opacity;if(r<1){n.css({opacity:r})}n.show();e.call()},function(e){this.getTip().hide();e.call()}],fade:[function(t){var n=this.getConf();if(!e.browser.msie||n.fadeIE){this.getTip().fadeTo(n.fadeInSpeed,n.opacity,t)}else{this.getTip().show();t()}},function(t){var n=this.getConf();if(!e.browser.msie||n.fadeIE){this.getTip().fadeOut(n.fadeOutSpeed,t)}else{this.getTip().hide();t()}}]};e.fn.tooltip=function(t){var n=this.data("{tooltip}");if(n){return n}t=e.extend(true,{},e.tools.tooltip.conf,t);if(typeof t.position=="string"){t.position=t.position.split(/,?\s/)}this.each(function(){n=new r(e(this),t);e(this).data("{tooltip}",n)});return t.api?n:this}})(jQuery);
// tooltip utils
(function(a){var b=a.tools.tooltip;b.dynamic={conf:{classNames:"top right bottom left"}};function c(b){var c=a(window),d=c.width()+c.scrollLeft(),e=c.height()+c.scrollTop();return[b.offset().top<=c.scrollTop(),d<=b.offset().left+b.width(),e<=b.offset().top+b.height(),c.scrollLeft()>=b.offset().left]}function d(a){var b=a.length;while(b--)if(a[b])return!1;return!0}a.fn.dynamic=function(e){typeof e=="number"&&(e={speed:e}),e=a.extend({},b.dynamic.conf,e);var f=a.extend(!0,{},e),g=e.classNames.split(/\s/),h;this.each(function(){var b=a(this).tooltip().onBeforeShow(function(b,e){var i=this.getTip(),j=this.getConf();h||(h=[j.position[0],j.position[1],j.offset[0],j.offset[1],a.extend({},j)]),a.extend(j,h[4]),j.position=[h[0],h[1]],j.offset=[h[2],h[3]],i.css({visibility:"hidden",position:"absolute",top:e.top,left:e.left}).show();var k=a.extend(!0,{},f),l=c(i);if(!d(l)){l[2]&&(a.extend(j,k.top),j.position[0]="top",i.addClass(g[0])),l[3]&&(a.extend(j,k.right),j.position[1]="right",i.addClass(g[1])),l[0]&&(a.extend(j,k.bottom),j.position[0]="bottom",i.addClass(g[2])),l[1]&&(a.extend(j,k.left),j.position[1]="left",i.addClass(g[3]));if(l[0]||l[2])j.offset[0]*=-1;if(l[1]||l[3])j.offset[1]*=-1}i.css({visibility:"visible"}).hide()});b.onBeforeShow(function(){var a=this.getConf(),b=this.getTip();setTimeout(function(){a.position=[h[0],h[1]],a.offset=[h[2],h[3]]},0)}),b.onHide(function(){var a=this.getTip();a.removeClass(e.classNames)}),ret=b});return e.api?ret:this}})(jQuery);
(function(a){var b=a.tools.tooltip;a.extend(b.conf,{direction:"up",bounce:!1,slideOffset:10,slideInSpeed:200,slideOutSpeed:200,slideFade:!a.browser.msie});var c={up:["-","top"],down:["+","top"],left:["-","left"],right:["+","left"]};b.addEffect("slide",function(a){var b=this.getConf(),d=this.getTip(),e=b.slideFade?{opacity:b.opacity}:{},f=c[b.direction]||c.up;e[f[1]]=f[0]+"="+b.slideOffset,b.slideFade&&d.css({opacity:0}),d.show().animate(e,b.slideInSpeed,a)},function(b){var d=this.getConf(),e=d.slideOffset,f=d.slideFade?{opacity:0}:{},g=c[d.direction]||c.up,h=""+g[0];d.bounce&&(h=h=="+"?"-":"+"),f[g[1]]=h+"="+e,this.getTip().animate(f,d.slideOutSpeed,function(){a(this).hide(),b.call()})})})(jQuery);

// Для ИЕ добавлен новый транспорт для кроссдоменных AJAX запросов
if (!jQuery.support.cors && window.XDomainRequest) {

	var httpRegEx = /^https?:\/\//i;
	var getOrPostRegEx = /^get|post$/i;
	var sameSchemeRegEx = new RegExp('^'+location.protocol, 'i');
	var xmlRegEx = /\/xml/i;

	// ajaxTransport exists in jQuery 1.5+
	jQuery.ajaxTransport('text html xml json', function(options, userOptions, jqXHR){
	// XDomainRequests must be: asynchronous, GET or POST methods, HTTP or HTTPS protocol, and same scheme as calling page
	if (options.crossDomain && options.async && getOrPostRegEx.test(options.type) && httpRegEx.test(userOptions.url) && sameSchemeRegEx.test(userOptions.url)) {
		var xdr = null;
		var userType = (userOptions.dataType||'').toLowerCase();
		return {
			send: function(headers, complete){
				xdr = new XDomainRequest();
				if (/^\d+$/.test(userOptions.timeout)) {
					xdr.timeout = userOptions.timeout;
				}
				xdr.ontimeout = function(){
					complete(500, 'timeout');
				};
				xdr.onload = function(){
					var allResponseHeaders = 'Content-Length: ' + xdr.responseText.length + '\r\nContent-Type: ' + xdr.contentType;
					var status = {
						code: 200,
						message: 'success'
					};
					var responses = {
						text: xdr.responseText
					};
						try {
							if (userType === 'json') {
								try {
									responses.json = JSON.parse(xdr.responseText);
								} catch(e) {
									status.code = 500;
									status.message = 'parseerror';
									//throw 'Invalid JSON: ' + xdr.responseText;
								}
							} else if ((userType === 'xml') || ((userType !== 'text') && xmlRegEx.test(xdr.contentType))) {
								var doc = new ActiveXObject('Microsoft.XMLDOM');
								doc.async = false;
								try {
									doc.loadXML(xdr.responseText);
								} catch(e) {
									doc = undefined;
								}
								if (!doc || !doc.documentElement || doc.getElementsByTagName('parsererror').length) {
									status.code = 500;
									status.message = 'parseerror';
									throw 'Invalid XML: ' + xdr.responseText;
								}
								responses.xml = doc;
							}
						} catch(parseMessage) {
							throw parseMessage;
						} finally {
							complete(status.code, status.message, responses, allResponseHeaders);
						}
					};
					xdr.onerror = function(){
						complete(500, 'error', {
							text: xdr.responseText
						});
					};
					xdr.open(options.type, options.url);
					//xdr.send(userOptions.data);
					xdr.send();
				},
				abort: function(){
					if (xdr) {
						xdr.abort();
					}
				}
			};
		}
	});
};

// DOM loaded
$(function(){
	$('[placeholder]').placeholderSetup();
	$('[rel=tooltip]').each(function(){
		var options = $(this).data('tooltip-options')||{};
		$(this).tooltip($.extend({
			effect: "slide",
			delay: 100
		}, options));
	});
	$('[rel=modal]').fancybox({
		live: true,
		height: '80%',
		autoResize: false,
		overlayShow: true,
		opacity: 0.8,
		centerOnScroll: true
	});
});

/**
 * Fancybox dropIn/dropOut transitions
 */
;(function($, F, undefined) {
	if (!F) return;
	// Opening animation - fly from the top
	F.transitions.dropIn = function() {
		var endPos = F._getPosition(true);
		endPos.top = (parseInt(endPos.top, 10) + 400) + 'px';
		endPos.opacity = 0;
		F.wrap.css(endPos).show().animate({
			top: '-=400px',
			opacity: 1
		}, {
			duration: F.current.openSpeed,
			complete: F._afterZoomIn
		});
	};
	// Closing animation - fly to the top
	F.transitions.dropOut = function() {
		F.wrap.removeClass('fancybox-opened').animate({
			top: '+=400px',
			opacity: 0
		}, {
			duration: F.current.closeSpeed,
			complete: F._afterZoomOut
		});
	};
}(jQuery, jQuery.fancybox));

// yandex geocoder autocomplete
$.fn.geocomplete = function(options){
	$.support.cors = true;
	return this.each(function(){
		$(this).auto_complete($.extend({
			url: 'http://geocode-maps.yandex.ru/1.x/',
			queryParamName: 'geocode',
			extraParams: {
				format: 'json',
				rspn: 0,
				results: 50,
				sco: 'latlong'
			},
			remoteDataType: 'json',
			resultsClass: 'acResultsCities',
			minChars: 1,
			maxItemsToShow: 0,
			autoFill: true,
			processData: function(res){
				var parsed=[];
				if (res && res.response){
					var objects = res.response.GeoObjectCollection.featureMember, obj;
					for(var i in objects){
						obj = objects[i].GeoObject;
						var meta = obj.metaDataProperty.GeocoderMetaData;
						parsed.push({
							value: obj.name,
							data: {
								bounds: [obj.boundedBy.Envelope.lowerCorner.split(' ').reverse(), obj.boundedBy.Envelope.upperCorner.split(' ').reverse()],
								center: obj.Point.pos.split(' ').reverse(),
								description: obj.description,
								text: meta.text,
								kind: meta.kind
							}
						});
					}
				}
				return parsed;
			},
			filterResults: false,
			sortResults: false,
			useCache: false,
			showResult:function(value, data){
				// function hl(str) {return str.replace(new RegExp("(" + input.val() + ")", "gi"), "<strong>$1</strong>");}
				return value + (data.description?'<br/><em>'+ data.description +'</em>':'');
			}
		}, options));
	});
};


/**
 * Initialize Rupor object
 */
;(function(w, d, $, undefined) {
if (!w.Rupor) Rupor = {cookieName: 'rupor'};

Rupor.debug = function() {
	if (this.debug && 'console' in window) window.console.log.apply(window.console, arguments);
}

Rupor.data = {
	$: $.extend({
		// defaults
			location: null
		},
		// from cookie
		$.parseJSON($.cookie(Rupor.cookieName)),
		// params
		Rupor.params
	),
	get: function(key){return key ? this.$[key] : this.$;},
	set: function(key, value){
		if (!key) return;
		var data = {};
		if ($.type(key)=='object') data = key; else data[key] = value;
		$.cookie(Rupor.cookieName, $.toJSON($.extend(this.$, data)), {expires: 365, domain: '.' + window.location.hostname});
	}
};

/**
 * Rupor Box object
 */
Rupor.box = {
	wide: function (opts){
		$.fancybox($.extend({
			type: 'ajax',
			openMethod: 'dropIn',
			closeMethod: 'dropOut',
			autoCenter: true,
			minWidth: '100%',
			minHeight: '100%',
			wrapCSS: 'box-wide',
			// aspectRatio: true,
			// scrolling: false,
			// padding: 30,
			fitToView: 1,
			margin: [90,0,0,0]
		}, opts));
		return false;
	},
	auth: function (opts){
		$.fancybox($.extend({
			autoCenter: false,
			autoResize: false,
			padding: 20,
			minHeight: 0,
			maxWidth: 400,
			wrapCSS: 'box-auth'
		}, opts));
		return false;
	},
	notice: function(msg, opts, callback){
		opts=opts||{};
		$.fancybox($.extend({
			centerOnScroll: true,
			padding: 50,
			minHeight: 0,
			maxWidth: 400,
			wrapCSS: 'box-notice',
			content: msg||'',
			afterShow: function(){
				setTimeout(function(){
					$.fancybox.close();
					if ($.isFunction(callback))
						callback();
				}, opts.hideDelay||1000);
			}
		}, opts));
		return false;
	},
	error: function(msg){
		this.notice(msg, {
			wrapCSS: 'box-error'
		})
		return false;
	}
};

Rupor.request = {
	getIcon: function(status){ // by status
		status = parseInt(status,10);
		switch(status){
			case(1): return 'twirl#greenDotIcon';
			case(2): return 'twirl#redDotIcon';
			default: return 'twirl#yellowDotIcon';
		}
	},
	subscribe: function(id, callback){
		$.ajax({
			url: '/request/subscribe',
			data: {id: id},
			dataType: 'json',
			success: function(res){
				if ($.isFunction(callback)) callback(res);
				Rupor.box.notice(res.message);
			},
			error: function(xhr){
				Rupor.box.error(xhr.responseText);
			}
		});
	},
	like: function(id, callback){
		$.ajax({
			url: '/request/like',
			data: {id: id},
			dataType: 'json',
			success: function(res){
				if ($.isFunction(callback)) callback(res);
				Rupor.box.notice(res.message);
			},
			error: function(xhr){
				Rupor.box.error(xhr.responseText);
			}
		});
		return false;
	}
}

Rupor.geocode = {
	getFullAddress: function(params, callback)
	{
		params.center = params.center[0] + ',' + params.center[1];
		$.getJSON('/request/geocode?', params, callback);
	}
};

Rupor.initHash = function(str){
	var str = str||Rupor.data.get('location');
	if (!str) return;
	str = decodeURIComponent(str);
	var ua = navigator.userAgent;
	var is_chrome = ua.indexOf('Chrome') > -1;
	var is_explorer = ua.indexOf('MSIE') > -1;
	var is_firefox = ua.indexOf('Firefox') > -1;
	var is_safari = ua.indexOf("Safari") > -1;
	var is_Opera = ua.indexOf("Presto") > -1;
	if (is_safari&&!is_chrome)
		str = encodeURIComponent(str);
	window.location.hash = str;
};

w.Rupor = Rupor;

})(window, document, jQuery);

/**
 * Placeholder jQuery plugin
 * @author vk.timo@gmail.com
 */
;(function($){
$.fn.placeholderSetup = function(){
	return $(this).each(function(){

		if ($(this).data('placeholder') == true) return;
			$(this).data('placeholder', true)

		var el = $(this), ph = el.attr('placeholder')||'';
		if (!ph) return;

		el.attr('placeholder','');
		var ph_wrap = $('<span/>').addClass('placeholder').css({display:'inline-block', position: 'relative'});
		var ph_text = $('<span/>').addClass('placeholder-text').css({
			position: 'absolute',top:el.css('top')||0,left:el.css('left')||0,
			zIndex: (parseInt(el.css('zIndex'))||0)+1,display:'inline-block',
			fontSize: el.css('fontSize'),
			fontFamily: el.css('fontFamily'),
			lineHeight: el.css('lineHeight'),
			padding: [el.css('padding-top'),el.css('padding-right'),el.css('padding-bottom'),el.css('padding-left')].join(' '),
			marginLeft: parseInt(el.css('margin-left'))+parseInt(el.css('border-left-width'))+'px',
			marginTop: parseInt(el.css('margin-top'))+parseInt(el.css('border-top-width'))+'px'
		}).text(ph);
		el.before(ph_wrap);
		ph_wrap.append(ph_text,el);
		// events
		el.on('focus', function(){ ph_text.hide(); })
		  .on('blur', function(){ if (el.val()=='') ph_text.show(); });
		ph_text.on('click', function(){ $(this).hide();el.focus(); });
		if (el.val())
			ph_text.hide();
	});
}
})(jQuery);


function array_unique( array ) {	// Removes duplicate values from an array
	//
	// +   original by: Carlos R. L. Rodrigues

	var p, i, j;
	for(i = array.length; i;){
		for(p = --i; p > 0;){
			if(array[i] === array[--p]){
				for(j = p; --p && array[i] === array[p];);
				i -= array.splice(p + 1, j - p).length;
			}
		}
	}

	return true;
}
