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
	var my_gall_obj = $('a[class*="pirobox"]');
	var map = new Object();
		for (var i=0; i<my_gall_obj.length; i++) {
			var it=$(my_gall_obj[i]);
			map['a.'+ it.attr('class').match(/^pirobox_gall\w*/)]=0;
		}
	var gall_settings = new Array();
	for (var key in map) {
		gall_settings.push(key);
	}
	for (var i=0; i<gall_settings.length; i++) {
		$(gall_settings[i]+':first').addClass('first');
		$(gall_settings[i]+':last').addClass('last');
	}

	var piro_gallery = $(my_gall_obj);
	$('a[class*="pirobox_gall"]').each(function(rev){this.rev = rev+0});
	var struct =(

                '<div id="fb-root"></div>'+
		'<div class="piro_overlay"></div>'+
		'<table class="piro_html"  cellpadding="0" cellspacing="0" >'+
		'<tr style="background-color: #000000;">'+
		'<td align="center">'+
		'<div class="piro_loader" title="Close"><span></span></div>'+
		'<div class="resize">'+
		'<div class="nav_container">'+
		'<a href="#prev" class="piro_prev" title="Prev"></a>'+
		'<a href="#next" class="piro_next" title="Next"></a>'+
		'<div class="piro_prev_fake">Prev</div>'+
		'<div class="piro_next_fake">Next</div>'+
               ' <div id="mac_close" style="margin-left:450px;float:left;display:none;position:absolute;"></div>'+
                '<div id="mac_cross" class="piro_close" onMouseOver="toggleDiv(1)" onMouseOut="toggleDiv(0)"></div>'+
		'</div>'+
                '<div class="clear"></div>'+
		'<div class="div_reg"></div>'+'</div>'+
               	'</td>'+
		'</tr>'+
                '<tr class="facebookcomment">'+
	        '<td class="" style="padding:0px;position:relative">'+
                '<table cellspacing="0" cellpadding="0" border="0">'+
                '<div style="position:relative"><div style="width:190px;padding:10px;position:absolute;"><div style="border-bottom:1px solid #ccc;line-height:13px;"><span style="padding:10px 10px 0 0;font-size:12px;color:#3B5998;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:bold;" class="macAlbum" ></span><br />'+
                '<span style="font-size:10px;color:#666;font-family:tahoma;font-weight:normal;">on</span> <span class="macDate" style="padding:0px 0px 4px 4px;font-size:11px;color:#3B5998;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:normal;"></span></div>'+
                '<div style="padding:5px 0 0 0;"><span padding:4px 2px 0 0;  class="downloadimage"></span><span padding:4px 2px 0 0;  class="macfb_url"></span></div></div>'+
                '<div id="facebook" style="padding:30px 20px 0 10px;margin-left:220px;"></div><div class"clear"></div>'+
                '<div style="height: 120px;margin-left: 666px;overflow: hidden;padding: 0px 5px 10px;position: absolute;top: 10px;vertical-align:top;width:270px;">'+
                '<div style="color:#3B5998;font-size:11px;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-weight:bold;">Description:</div>'+
                '<div style="color:#333;height:300px;text-align:justify;font-size:11px;font-family:lucida grande,tahoma,verdana,arial,sans-serif" class="macDesc"></div>'+
                '<div class="clear"></div></div></table></td>'+
                '</tr>'+


                '</table>'
		);
	$('body').append(struct);
	var wrapper = $('.piro_html'),
	piro_capt = $('.caption'),
        piro_desc = $('.macDesc'),
        piro_date  = $('.macDate'),
        piro_albname = $('.macAlbum'),
	piro_bg = $('.piro_overlay'),
	piro_next = $('.piro_next'),
        image_download = $('.downloadimage'),
        image_fburl    = $('.macfb_url'),
	piro_prev = $('.piro_prev'),
	piro_next_fake = $('.piro_next_fake'),
	piro_prev_fake = $('.piro_prev_fake'),
	piro_close = $('.piro_close'),
	div_reg = $('.div_reg'),
	piro_loader = $('.piro_loader'),
	resize = $('.resize'),
	btn_info = $('.btn_info');
	var rz_img =0.95; /*::::: ADAPT IMAGE TO BROWSER WINDOW SIZE :::::*/
//	if ( $.browser.msie ) {
//		wrapper.draggable({ handle:'.h_t_c,.h_b_c,.div_reg img'});
//	}else{
//		wrapper.draggable({ handle:'.h_t_c,.h_b_c,.div_reg img',opacity: 0.80});
//	}
var y = $(window).height();
	//var x =document.getElementById("wrapper").style.width;

	$('.nav_container').hide();
	//wrapper.css({left:  ((x/2)-(250))+ 'px',top: parseInt($(document).scrollTop())+(100)});
	$(wrapper).add(piro_capt).add(piro_bg).hide();
	piro_bg.css({'opacity':opt.bg_alpha});
	$(piro_prev).add(piro_next).bind('click',function(c) {
		$('.nav_container').hide();
		c.preventDefault();
		piro_next.add(piro_prev).hide();
		var obj_count = parseInt($('a[class*="pirobox_gall"]').filter('.item').attr('rev'));
		var start = $(this).is('.piro_prev') ? $('a[class*="pirobox_gall"]').eq(obj_count - 1) : $('a[class*="pirobox_gall"]').eq(obj_count + 1);
		start.click();
	});
	$('html').bind('keyup', function (c) {
		 if(c.keyCode == 27) {
			c.preventDefault();
			if($(piro_close).is(':visible')){close_all();}
		}
	});
	$('html').bind('keyup' ,function(e) {
		 if ($('.item').is('.first')){
		}else if(e.keyCode == 37){
		e.preventDefault();
			if($(piro_close).is(':visible')){piro_prev.click();}
		 }
	});
	$('html').bind('keyup' ,function(z) {
		if ($('.item').is('.last')){
		}else if(z.keyCode == 39){
		z.preventDefault();
			if($(piro_close).is(':visible')){piro_next.click();}
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
	$(piro_gallery).each(function(){

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
                var fbUrl = 'http://www.facebook.com/dialog/feed?app_id='+macapi+'&description='+ldescr+'&picture='+p_link+'&name='+descr+'&message=Comments&redirect_uri='+site_url+'/';
                var img_site = site_url+'/wp-content/plugins/'+mac_folder+'/macdownload.php?albid='+img_id;

		$(this).unbind();
		$(this).bind('click', function(e) {

                        var new_y = $(window).height();
			var new_x = $(window).width();
			var new_h = wrapper.height();
			var new_w = wrapper.width();
                        var h = parseInt($(document).scrollTop())+parseInt((new_y-new_h)/2)-50;

			//var k = h+25;
			wrapper.css({
				left:  ((new_x/2)-(new_w/2)+10)+ 'px',
				top: h+ 'px'
			});
			piro_bg.css({'opacity':opt.bg_alpha});
			e.preventDefault();
			piro_next.add(piro_prev).hide().css('visibility','hidden');
			$(piro_gallery).filter('.item').removeClass('item');
			$(this).addClass('item');
			open_all();
			if($(this).is('.first')){

                        if($(this).is('.first') && $(this).is('.last')){ }else{
				piro_prev.hide()
				piro_next.show();
				piro_prev_fake.show().css({'opacity':0.5,'visibility':'hidden'});}
			}else{
				piro_next.add(piro_prev).show();
				piro_next_fake.add(piro_prev_fake).hide();
			}
			if($(this).is('.last')){
                            if($(this).is('.first') && $(this).is('.last')){ }else{
				piro_prev.show();
				piro_next_fake.show().css({'opacity':0.5,'visibility':'hidden'});
				piro_next.hide();}
			}
			if($(this).is('.pirobox')){
				piro_next.add(piro_prev).hide();
			}

		});

	function open_all(){

			wrapper.add(piro_bg).add(div_reg).add(piro_loader).show();
			function animate_html(){

				if(params[1] == 'full' && params[2] == 'full'){
				params[2] = $(window).height()-70;
				params[1] = $(window).width()-55;
				}
				var y = $(window).height();
				var x = $(window).width();
				piro_close.hide();
				div_reg.add(resize).animate({
					'min-height':+ (params[2]) +'px',
					'width':+ (params [1])+'px'
					},opt.piro_speed).css('visibility','visible');

				wrapper.animate({
					height:+ (params[2])+20 +'px',
					width:+ (params[1]) +20+'px',
					left:  ((x/2)-((params[1])/2+10))+ 'px',
					top: parseInt($(document).scrollTop())+(y-params[2])/2-10
					},opt.piro_speed ,function(){
						piro_next.add(piro_prev).css({'height':'20px','width':'20px'});
						piro_next.add(piro_prev).add(piro_prev_fake).add(piro_next_fake).css('visibility','visible');
						$('.nav_container').show();
						piro_close.show();
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
								$('.div_reg img').remove();
								$('.div_reg').html('');
								div_reg.append(img).show();
							$(img).addClass('immagine');

							div_reg.add(resize).animate({height:520+'px',width:980+'px'},opt.piro_speed);
							wrapper.animate({
								height : 520 + 'px' ,
								width : 1000 + 'px' ,
                                                              left:  (x-980)/2 + 'px'
								//top: parseInt($(document).scrollTop())+(y-imgH)/2-20
								},opt.piro_speed, function(){
									var cap_w = resize.width();
									piro_capt.css({width:cap_w+'px'});
									piro_loader.hide();
									$(img).fadeIn(300,function(){
									piro_close.add(btn_info).show();
									piro_capt.slideDown(200);
									piro_next.add(piro_prev).css({'height':'20px','width':'20px'});
									piro_next.add(piro_prev).add(piro_prev_fake).add(piro_next_fake).css('visibility','visible');
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
						piro_loader.click(function(){
						img.src = 'about:blank';
					});
				}

			switch (params[0]) {

				case 'iframe':
					div_reg.html('').css('overflow','visible');
					resize.css('overflow','visible');
					piro_close.add(btn_info).add(piro_capt).hide();
					animate_html();
					div_reg.piroFadeIn(300,function(){
						div_reg.append(
						'<iframe id="my_frame" class="my_frame" src="'+p_link+'" frameborder="0" allowtransparency="true" scrolling="auto" align="top"></iframe>'
						);
						$('.my_frame').css({'height':+ (params[2]) +'px','width':+ (params [1])+'px'});
						piro_loader.hide();
					});
				break;

				case 'content':
					div_reg.html('').css('overflow','auto');
					resize.css('overflow','auto');
					$('.my_frame').remove();
					piro_close.add(btn_info).add(piro_capt).hide();
					animate_html()
					div_reg.piroFadeIn(300,function(){
						div_reg.load(p_link);
						piro_loader.hide();
					});
				break;

				case 'inline':
					div_reg.html('').css('overflow','auto');
					resize.css('overflow','auto');
					$('.my_frame').remove();
					piro_close.add(btn_info).add(piro_capt).hide();
					animate_html()
					div_reg.piroFadeIn(300,function(){
						$(p_link).clone(true).appendTo(div_reg).piroFadeIn(300);
						piro_loader.hide();
					});
				break

				case 'gallery':
					div_reg.css('overflow','visible');
					resize.css('overflow','visible');
					$('.my_frame').remove();
					piro_close.add(btn_info).add(piro_capt).hide();
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
						piro_albname.html('No Album name is available for this image');
						}else{
					piro_albname.html( albname );
					}



					animate_image();
				break;

				case 'single':
					piro_close.add(btn_info).add(piro_capt).hide();
					div_reg.html('').css('overflow','hidden');
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
		if($('.piro_close').is(':visible')){
			$('.my_frame').remove();
			wrapper.add(div_reg).add(resize).stop();
			var ie_sucks = wrapper;
			if ( $.browser.msie ) {
			ie_sucks = div_reg.add(piro_bg);
			$('.div_reg img').remove();
			}else{
			ie_sucks = wrapper.add(piro_bg);
			}
			ie_sucks.piroFadeOut(200,function(){
				div_reg.html('');
				piro_loader.add(piro_capt).add(btn_info).hide();
				$('.nav_container').hide();
				piro_bg.add(wrapper).hide().css('visibility','visible');
			});
			}
		}
		piro_close.add(piro_loader).add(piro_bg).bind('click',function(y){y.preventDefault();close_all();});
	}
})(jQuery);
 function toggleDiv(id)
{
var eff = jQuery.noConflict();
if (id=="1"){

eff('#mac_close').show();
}
else
if (id=="0"){
eff('#mac_close').hide();
}
}