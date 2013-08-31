/**
* Name: PiroBox Extended v.1.0
* Date: Gen 2011
* Autor: Diego Valobra (http://www.pirolab.it),(http://www.diegovalobra.com)
* Version: 1.0
* Licence: CC-BY-SA http://creativecommons.org/licenses/by-sa/3/it/
**/

(function($) {
	$.fn.piroBox_ext = function(opt) {
		opt = jQuery.extend({
		piro_speed : 700,
		bg_alpha : 0.9,
		piro_scroll : true
		}, opt);
	$.fn.piroFadeIn = function(speed, callback) {
		$(this).fadeIn(speed, function() {
		if(jQuery.browser.msie)
			$(this).get(0).style.removeAttribute('filter');
		if(callback != undefined)
			callback();
		});
	};
	$.fn.piroFadeOut = function(speed, callback) {
		$(this).fadeOut(speed, function() {
		if(jQuery.browser.msie)
			$(this).get(0).style.removeAttribute('filter');
		if(callback != undefined)
			callback();
		});
	};
	var my_gall_objw = $('a[class*="pirobox"]');
	var mapw = new Object();
		for (var i=0; i<my_gall_objw.length; i++) {
			var it=$(my_gall_objw[i]);
			mapw['a.'+ it.attr('class').match(/^pirobox_macgallery\w*/)]=0;
		}
	var gall_settingsw = new Array();
	for (var key in mapw) {
		gall_settingsw.push(key);
	}
	for (var i=0; i<gall_settingsw.length; i++) {
		$(gall_settingsw[i]+':first').addClass('first');
		$(gall_settingsw[i]+':last').addClass('last');
	}

	var piro_galleryw = $(my_gall_objw);
	$('a[class*="pirobox_macgallery"]').each(function(rev){this.rev = rev+0});;
	var structw =(

                '<div id="fb-root"></div>'+
		'<div class="piro_overlayw"></div>'+
		'<table class="piro_htmlw"  cellpadding="0" cellspacing="0" >'+
		'<tr style="background-color: #000000;">'+
		'<td align="center">'+
		'<div class="piro_loaderw" title="close"><span></span></div>'+
		'<div class="resizew">'+
		'<div class="nav_container">'+
		'<a href="#prev" class="piro_prevw" title="previous"></a>'+
		'<a href="#next" class="piro_nextw" title="next"></a>'+
		'<div class="piro_prevw_fake">prev</div>'+
		'<div class="piro_nextw_fake">next</div>'+
		'<div class="piro_closew" title="close"></div>'+
		'</div>'+
		'<div class="div_regw"></div>'+'</div>'+
               	'</td>'+
		'</tr>'+
                '<tr class="facebookcomment">'+
	        '<td class="" style="padding:0px;position:relative">'+
                '<table cellspacing="0" cellpadding="0" border="0">'+
                '<div style="position:relative"><div style="width:190px;padding:10px;position:absolute;"><div style="border-bottom:1px solid #ccc;line-height:13px;"><span style="padding:10px 10px 0 0;font-size:12px;color:#3B5998;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:bold;" class="macAlbum" ></span><br />'+
                '<span style="font-size:10px;color:#666;font-family:tahoma;font-weight:normal;">on</span> <span class="macDate" style="padding:0px 0px 4px 4px;font-size:11px;color:#3B5998;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:normal;"></span></div>'+
                '<div style="padding:5px 0 0 0;"><span padding:4px 2px 0 0;  class="downloadimage"></span><span padding:4px 2px 0 0;  class="macfb_url"></span></div></div>'+
                '<div id="facebook" style="padding:10px 20px 0 10px;margin-left:220px;"></div><div class"clear"></div>'+
                '<div style="height: 120px;margin-left: 666px;overflow: hidden;padding: 0px 5px 10px;position: absolute;top: 10px;vertical-align:top;width:270px;">'+
                '<div style="color:#3B5998;font-size:11px;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:bold;">Description:</div>'+
                '<div style="color:#333;height:300px;text-align:justify;font-size:11px;font-family:lucida grande,tahoma,verdana,arial,sans-serif" class="macDesc"></div>'+
                '<div class="clear"></div></div></table></td>'+
                '</tr>'+


                '</table>'
		);
	$('body').append(structw);
	var wrapper = $('.piro_htmlw'),
	piro_capt = $('.caption'),
        piro_desc = $('.macDesc'),
        piro_date  = $('.macDate'),
        piro_albname = $('.macAlbum'),
	piro_bg = $('.piro_overlayw'),
	piro_nextw = $('.piro_nextw'),
        image_download = $('.downloadimage'),
        image_fburl    = $('.macfb_url'),
	piro_prevw = $('.piro_prevw'),
	piro_nextw_fake = $('.piro_nextw_fake'),
	piro_prevw_fake = $('.piro_prevw_fake'),
	piro_closew = $('.piro_closew'),
	div_regw = $('.div_regw'),
	piro_loaderw = $('.piro_loaderw'),
	resize = $('.resizew'),
	btn_info = $('.btn_info');
	var rz_img =0.95; /*::::: ADAPT IMAGE TO BROWSER WINDOW SIZE :::::*/
//	if ( $.browser.msie ) {
//		wrapper.draggable({ handle:'.h_t_c,.h_b_c,.div_regw img'});
//	}else{
//		wrapper.draggable({ handle:'.h_t_c,.h_b_c,.div_regw img',opacity: 0.80});
//	}
var y = $(window).height();
	var x =document.getElementById("wrapper").style.width;

	$('.nav_container').hide();
	//wrapper.css({left:  ((x/2)-(250))+ 'px',top: parseInt($(document).scrollTop())+(100)});
	$(wrapper).add(piro_capt).add(piro_bg).hide();
	piro_bg.css({'opacity':opt.bg_alpha});
	$(piro_prevw).add(piro_nextw).bind('click',function(c) {
		$('.nav_container').hide();
		c.preventDefault();
		piro_nextw.add(piro_prevw).hide();
		var obj_count = parseInt($('a[class*="pirobox_macgallery"]').filter('.item').attr('rev'));
		var start = $(this).is('.piro_prevw') ? $('a[class*="pirobox_macgallery"]').eq(obj_count - 1) : $('a[class*="pirobox_macgallery"]').eq(obj_count + 1);
		start.click();
	});
	$('html').bind('keyup', function (c) {
		 if(c.keyCode == 27) {
			c.preventDefault();
			if($(piro_closew).is(':visible')){close_all();}
		}
	});
	$('html').bind('keyup' ,function(e) {
		 if ($('.item').is('.first')){
		}else if(e.keyCode == 37){
		e.preventDefault();
			if($(piro_closew).is(':visible')){piro_prevw.click();}
		 }
	});
	$('html').bind('keyup' ,function(z) {
		if ($('.item').is('.last')){
		}else if(z.keyCode == 39){
		z.preventDefault();
			if($(piro_closew).is(':visible')){piro_nextw.click();}
		}
	});
	$(window).resize(function(){
		var new_y = $(window).height();
			var new_x = $(window).width();
			var new_h = wrapper.height();
			var new_w = wrapper.width();
                        h =  parseInt($(document).scrollTop()-80);

			wrapper.css({
				left:  ((new_x/2)-(new_w/2)+10)+ 'px',
				top: h+ 'px'
			});
	});
	function scrollIt (){
		$(window).scroll(function(){
			var new_y = $(window).height();
			var new_x = $(window).width();
			var new_h = wrapper.height();
			var new_w = wrapper.width();
                        h =  parseInt($(document).scrollTop()-80);

			wrapper.css({
				left:  ((new_x/2)-(new_w/2)+10)+ 'px',
				top: h+ 'px'
			});
		});
	}
	if(opt.piro_scroll == true){
		//scrollIt()
	}
	$(piro_galleryw).each(function(){

		var descr  = $(this).attr('title');
                var ldescr = $(this).attr('name');
                var date   = $(this).attr('date');
                var albname = $(this).attr('albname');
                var macapi  = $(this).attr('macapi_id');
                var dateSplit = date.split("-");
                var dateDis  = dateSplit[2]+'-'+dateSplit[1]+'-'+dateSplit[0];

              	var params = $(this).attr('rel').split('-');
		var p_link = $(this).attr('href');
                var img_url = p_link.split("/");
                var img_id  = img_url[img_url.length-1];
                var fbUrl = 'http://www.facebook.com/dialog/feed?app_id='+macapi+'&description='+ldescr+'&picture='+p_link+'&name='+descr+'&message=dfsdfs&redirect_uri='+img_url[0]+'//'+img_url[2]+'/'+img_url[3]+'/';
                var img_site = img_url[0]+'//'+img_url[2]+'/'+img_url[3]+'/'+img_url[4]+'/'+img_url[5]+'/'+img_url[6]+'/macdownload.php?albid='+img_id;

		$(this).unbind();
		$(this).bind('click', function(e) {

                        var new_y = $(window).height();
			var new_x = $(window).width();
			var new_h = wrapper.height();
			var new_w = wrapper.width();
                        var h = parseInt($(document).scrollTop())+parseInt((new_y-new_h)/2)-80;

			wrapper.css({
				left:  ((new_x/2)-(new_w/2)+10)+ 'px',
				top: h+ 'px'
			});
			piro_bg.css({'opacity':opt.bg_alpha});
			e.preventDefault();
			piro_nextw.add(piro_prevw).hide().css('visibility','hidden');
			$(piro_galleryw).filter('.item').removeClass('item');
			$(this).addClass('item');
			open_all();
			if($(this).is('.first')){
				piro_prevw.hide();
				piro_nextw.show();
				piro_prevw_fake.show().css({'opacity':0.5,'visibility':'hidden'});
			}else{
				piro_nextw.add(piro_prevw).show();
				piro_nextw_fake.add(piro_prevw_fake).hide();
			}
			if($(this).is('.last')){
				piro_prevw.show();
				piro_nextw_fake.show().css({'opacity':0.5,'visibility':'hidden'});
				piro_nextw.hide();
			}
			if($(this).is('.pirobox')){
				piro_nextw.add(piro_prevw).hide();
			}

		});

	function open_all(){

			wrapper.add(piro_bg).add(div_regw).add(piro_loaderw).show();
			function animate_html(){

				if(params[1] == 'full' && params[2] == 'full'){
				params[2] = $(window).height()-70;
				params[1] = $(window).width()-55;
				}
				var y = $(window).height();
				var x = $(window).width();
				piro_closew.hide();
				div_regw.add(resize).animate({
					'min-height':+ (params[2]) +'px',
					'width':+ (params [1])+'px'
					},opt.piro_speed).css('visibility','visible');

				wrapper.animate({
					height:+ (params[2])+20 +'px',
					width:+ (params[1]) +20+'px',
					left:  ((x/2)-((params[1])/2+10))+ 'px',
					top: parseInt($(document).scrollTop())+(y-params[2])/2-10
					},opt.piro_speed ,function(){
						piro_nextw.add(piro_prevw).css({'height':'20px','width':'20px'});
						piro_nextw.add(piro_prevw).add(piro_prevw_fake).add(piro_nextw_fake).css('visibility','visible');
						$('.nav_container').show();
						piro_closew.show();
				});
			}
			function animate_image (){
						var img = new Image();
						img.onerror = function (){
							piro_capt.html('');
							img.src = "http://www.pirolab.it/pirobox/js/error.jpg";
						}
						img.onload = function() {
							piro_capt.add(btn_info).hide();
							var y = 560;
							var x = wrapper.width();
							var	imgH = img.height;
							var	imgW = img.width;

							//var rz_img =1.203; /*::::: ORIGINAL SIZE :::::*/
							if(imgH+20 > y || imgW+20 > x){
								var _x = (imgW + 20)/x;
								var _y = (imgH + 20)/y;
								if ( _y > _x ){
									imgW = Math.round(img.width* (rz_img/_y));
									imgH = Math.round(img.height* (rz_img/_y));
								}else{
									imgW = Math.round(img.width * (rz_img/_x));
									imgH = Math.round(img.height * (rz_img/_y));
								}
							}else{
								 imgH = img.height;
								 imgW = img.width;
								}

							var y = $(window).height();
							var x = $(window).width();
							$(img).height(imgH).width(imgW).hide();

							$(img).fadeOut(300,function(){});
								$('.div_regw img').remove();
								$('.div_regw').html('');
								div_regw.append(img).show();
							$(img).addClass('immagine');

							div_regw.add(resize).animate({height:520+'px',width:940+'px'},opt.piro_speed);
							wrapper.animate({
								height : 520 + 'px' ,
								width : 960 + 'px' ,
                                                              left:  (x-940)/2 + 'px'
								//top: parseInt($(document).scrollTop())+(y-imgH)/2-20
								},opt.piro_speed, function(){
									var cap_w = resize.width();
									piro_capt.css({width:cap_w+'px'});
									piro_loaderw.hide();
									$(img).fadeIn(300,function(){
									piro_closew.add(btn_info).show();
									piro_capt.slideDown(200);
									piro_nextw.add(piro_prevw).css({'height':'20px','width':'20px'});
									piro_nextw.add(piro_prevw).add(piro_prevw_fake).add(piro_nextw_fake).css('visibility','visible');
									$('.nav_container').show();
									resize.resize(function(){
										NimgW = img.width;//1.50;
										NimgH = img.heigh;//1.50;
										piro_capt.css({width:(NimgW)+'px'});
									});
								});
							});
						}

						img.src = p_link;
						piro_loaderw.click(function(){
						img.src = 'about:blank';
					});
				}

			switch (params[0]) {

				case 'iframe':
					div_regw.html('').css('overflow','visible');
					resize.css('overflow','visible');
					piro_closew.add(btn_info).add(piro_capt).hide();
					animate_html();
					div_regw.piroFadeIn(300,function(){
						div_regw.append(
						'<iframe id="my_frame" class="my_frame" src="'+p_link+'" frameborder="0" allowtransparency="true" scrolling="auto" align="top"></iframe>'
						);
						$('.my_frame').css({'height':+ (params[2]) +'px','width':+ (params [1])+'px'});
						piro_loaderw.hide();
					});
				break;

				case 'content':
					div_regw.html('').css('overflow','auto');
					resize.css('overflow','auto');
					$('.my_frame').remove();
					piro_closew.add(btn_info).add(piro_capt).hide();
					animate_html()
					div_regw.piroFadeIn(300,function(){
						div_regw.load(p_link);
						piro_loaderw.hide();
					});
				break;

				case 'inline':
					div_regw.html('').css('overflow','auto');
					resize.css('overflow','auto');
					$('.my_frame').remove();
					piro_closew.add(btn_info).add(piro_capt).hide();
					animate_html()
					div_regw.piroFadeIn(300,function(){
						$(p_link).clone(true).appendTo(div_regw).piroFadeIn(300);
						piro_loaderw.hide();
					});
				break

				case 'pirobox_macgallery':
					div_regw.css('overflow','visible');
					resize.css('overflow','visible');
					$('.my_frame').remove();
					piro_closew.add(btn_info).add(piro_capt).hide();
					if(descr == ""){
						piro_capt.html('');
						}else{
					piro_capt.html('<p>' + descr + '</p>');
					}

image_fburl.html('<a class="links" title="Facebook Share" href="'+fbUrl+'" target="_blank" style="display:block;font-size:11px;color:#3B5998;font-family:tahoma;font-weight:normal;text-decoration: underline;">Share</a>');
image_download.html('<a href="'+img_site+'" style="display:block;font-size:11px;color:#3B5998;font-family:tahoma;font-weight:normal;text-decoration: underline;">Download</a>');

                                        if(ldescr == ""){
						piro_desc.html('No description is available for this image');
						}else{
					piro_desc.html( ldescr );
					}

                                          if(dateDis == ""){
						piro_date.html('No description is available for this image');
						}else{
					piro_date.html( dateDis );
					}

                                        if(albname == ""){
						piro_albname.html('No description is available for this image');
						}else{
					piro_albname.html( albname );
					}



					animate_image();
				break;
case 'pirobox_gall':
					div_regw.css('overflow','visible');
					resize.css('overflow','visible');
					$('.my_frame').remove();
					piro_closew.add(btn_info).add(piro_capt).hide();
					if(descr == ""){
						piro_capt.html('');
						}else{
					piro_capt.html('<p>' + descr + '</p>');
					}

image_fburl.html('<a class="links" title="Facebook Share" href="'+fbUrl+'" target="_blank" style="display:block;font-size:11px;color:#3B5998;font-family:tahoma;font-weight:normal;text-decoration: underline;">Share</a>');
image_download.html('<a href="'+img_site+'" style="display:block;font-size:11px;color:#3B5998;font-family:tahoma;font-weight:normal;text-decoration: underline;">Download</a>');

                                        if(ldescr == ""){
						piro_desc.html('No description is available for this image');
						}else{
					piro_desc.html( ldescr );
					}

                                          if(dateDis == ""){
						piro_date.html('No description is available for this image');
						}else{
					piro_date.html( dateDis );
					}

                                        if(albname == ""){
						piro_albname.html('No description is available for this image');
						}else{
					piro_albname.html( albname );
					}



					animate_image();
				break;
				case 'single':
					piro_closew.add(btn_info).add(piro_capt).hide();
					div_regw.html('').css('overflow','hidden');
					resize.css('overflow','visible');
					$('.my_frame').remove();
					if(descr == ""){
						piro_capt.html('');
						}else{
					piro_capt.html('<p>' + descr + '</p>');
					}
					animate_image();
				break
			}
		}
	});
		$('.immagine').live('click',function(){
		piro_capt.slideToggle(200);
	});

	function close_all (){
		if($('.piro_closew').is(':visible')){
			$('.my_frame').remove();
			wrapper.add(div_regw).add(resize).stop();
			var ie_sucks = wrapper;
			if ( $.browser.msie ) {
			ie_sucks = div_regw.add(piro_bg);
			$('.div_regw img').remove();
			}else{
			ie_sucks = wrapper.add(piro_bg);
			}
			ie_sucks.piroFadeOut(200,function(){
				div_regw.html('');
				piro_loaderw.add(piro_capt).add(btn_info).hide();
				$('.nav_container').hide();
				piro_bg.add(wrapper).hide().css('visibility','visible');
			});
			}
		}
		piro_closew.add(piro_loaderw).add(piro_bg).bind('click',function(y){y.preventDefault();close_all();});
	}
})(jQuery);