!(function( $ ) {
		'use strict';
	var cache = {};

	var useCache = function(opt) {
		if (!opt) return;
		var q="";
		if(typeof opt === "object"){
			$.each(opt,function(key,val){
				if(key!='data'){
					if(typeof val === "object"){
						var ret='';
						$.each(val,function(k,v){
							ret+=(k+':'+v)+','; 
						});
						val=ret;
					}
					q+=(key+':'+val)+',';
				}
			});
		}else{
			q=opt;
		}
		var cacheKey = window.btoa(unescape(encodeURIComponent( q )));
		if (opt.data){
			cache.data[cacheKey] = opt.data;
		}
		if (cache.data[cacheKey]) return cache.data[cacheKey];
		return null;
	}
	var flushCache = function(){
		cache = {};
		cache.length = 0;
		cache.data = {};
	}
	flushCache();
    $.fn.searchRw = function(params) {
    	var planified_posts = (params && params.data.planified_posts) ? params.data.planified_posts : false;
    	var data_search = (params && params.data)? params.data : {};
    	var ajax_search = (params && params.ajax)? params.ajax : {};
    	var func_search = (params && params.func)? {func:params.func} : {};
    	var pSear = $(this),
	    init_data_search = $.extend({
	    	per_page:20,
  			action: 'wp-link-ajax',
  			page:1,
  			get_planified_posts: planified_posts,
	    },data_search),
    	init_ajax_search = $.extend({
			url: "/wp-admin/admin-ajax.php",
			dataType: "json",
			type:"POST",
			cache:true,
			timeout: 50000,
			tryCount : 0,
			retryLimit : 3,
			data: init_data_search,
			success:function(data){
				useCache({q:valQuery,sendData:init_data_search,data:data});
				completeListSearch({el:pSear,data:data,page:1});
			},
			complete: function(){
				pSear.find('spinner').hide();
			}	    	
	    },ajax_search),
	    xhrAc,
	    valQuery,
	    liLink = $.extend({
	    	func : function(e) {	
						return false;
					}
	    	},func_search);
	    function completeListSearch(opt){	
			var el = opt.el,
			data = opt.data,
			page = (opt.page)?opt.page:1;
			var per_page = (opt.per_page)?opt.per_page:20;
			var ulParent=el,
			divRes = el.find('.query-results'),
			ul=divRes.find('ul'),
			noResult=divRes.find('.query-nothing'),
			i=0;
			if(page<=1){
				ul.html('');
				divRes.scrollTop(0);
				ulParent.attr('data-page',page);
			}
			$.each(data,function(key, value){
				if(value.ID && value.title){
					i++;
					var li = $( '<li></li>' ).append($("<span></span>").addClass('item-title').html(value.title))
					.append($("<span></span>").addClass('item-info').html(value.info))
					.attr('data-id',value.ID);
					li.click(function(){
						liLink.func(this);
					});
					if(key%2)li.addClass("alternate");
					li.appendTo(ul);
				}
			});
			if(i >= per_page){
				var next_page = (parseInt(ulParent.attr('data-page')))?parseInt(ulParent.attr('data-page'))+1:2;
				ulParent.attr('data-page',next_page);
				divRes.attr('data-scroll','on');
			}else{
				divRes.attr('data-scroll','off');
			}
			if(ul.find('li').length==0){
				noResult.show();
			}else{
				noResult.hide();
			}
			divRes.show();
			el.find('.spinner').hide();		
		}
    	return this.each(
    		function(){
    			var s=$(this).find('input[type=search]'),
    			lo=pSear.find('.search-rw-load'),
				resul = $(this).find('.query-results'),
    			tUl=resul.find('ul'),
				waiting = resul.find('.spinner');
				lo.css({visibility:'visible',display:'none'});
				s.keyup(function( event ) {

					if(xhrAc && xhrAc.readystate != 4){
						xhrAc.abort();
					}
					var valInput=$(this).val();
					var regS=/([\/()\[\]?!*%:;.,'\s])/gi;
					var valQueryLength = 3;
					if (!isNaN(valQuery)) {
						valQueryLength =1;
					}
					valQuery=valInput.replace(regS,"+");
					valQuery = valQuery.toLowerCase();
					if(valQuery.length >=valQueryLength){
						lo.css('display','block');
						waiting.hide();
						var sendData = init_data_search;
						if($(this).attr('data-exclude')){
							sendData.exclude=$(this).attr('data-exclude');
						}
						var data = useCache({q:valQuery,sendData:sendData});
						sendData.search = valQuery;
						sendData._ajax_linking_nonce = $('#_ajax_linking_nonce').val();
						var ajax_send = $.extend({},init_ajax_search,{
							data:sendData
						});
						if(data){
							completeListSearch({el:pSear,data:data,page:1});
						}else{
							xhrAc=$.ajax(ajax_send);
						}					
					}else{
						lo.hide();
						tUl.html('');
					}


				}).keydown(function( event ) {
				  if ( event.which == 13 ) {
				    event.preventDefault();
				  }
				});
    			resul.scroll(function(){
    				var el = $(this),
    				bottom = el.scrollTop() + el.height(),
					ul = el.find('ul'),
					ready = (el.attr('data-scroll') == 'on' )?true:false;
					if ( ! ready || bottom < ul.height() - 5)
						return;

					var newTop = el.scrollTop();
					if(xhrAc && xhrAc.readystate != 4){
						xhrAc.abort();
					}

					waiting.css({visibility:'visible',display:'block'});
					el.scrollTop( newTop + waiting.outerHeight() );
					el.attr('data-scroll','off');
					var sendData = init_data_search;
					if($(this).attr('data-exclude')){
						sendData.exclude=$(this).attr('data-exclude');
					}
					var data = useCache({q:valQuery,page:pSear.attr('data-page')});
					if(data){
						completeListSearch({el:pSear,data:data,page:pSear.attr('data-page')});
					}else{
						sendData.search = valQuery;
						sendData.page = pSear.attr('data-page');
						sendData._ajax_linking_nonce = $('#_ajax_linking_nonce').val();
						var ajax_send = $.extend({},init_ajax_search,{
							data:sendData,
							success:function(data){
								useCache({q:valQuery,page:pSear.attr('data-page'), data:data});
								completeListSearch({el:pSear,data:data,page:pSear.attr('data-page')});
							}
						});
						xhrAc=$.ajax(ajax_send);								
					}

    			});
			}
		);
    }
})(jQuery);