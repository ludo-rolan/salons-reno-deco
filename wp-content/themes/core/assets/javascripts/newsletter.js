$ = jQuery;

var remove_btn_html = '<a class="remove">X</a>';
articles_gotten_from_db = [];
new_articles_added = [];
function f_articles_to_exclude(){
	console.log("function f_articles_to_exclude");
	return __articles_to_exclude.join();
}

function addZero(i) {
    if (i < 10) {
        i = "0" + i.toString();
    }
    return i.toString();
}

function setMassageNl(nbrMin){
		var $ = jQuery;
		if(nbrMin != 0 && nbrMin != "")
			$("#nlmessagereste").fadeOut("slow",function(){
				$("#nlmessagereste").html(messageMustArt.replace("{param}",nbrMin)).fadeIn("fast");
			});
		else
			$("#nlmessagereste").html("");
}

function choose_an_article() {
	var $ = jQuery;
	var $__this = $(this);
	if($__this.is(":checked")) {
		$__this.attr("checked", "checked");
		$__this.hide();

		var id = $__this.val();
		var a_new_line = $__this.parent().html();
		a_new_line = remove_btn_html + a_new_line;

		var  selected_html = $('.list_selected_articles').html();
		selected_html += '<li id=line_'+id+'>'+a_new_line+'</li>';
		$('.list_selected_articles').html(selected_html);

		// bind remove action to remove button
		remove_selected_article() ;

		// add article object to choosen article array
		select_an_article($__this);
		$__this.parent().remove();

	} else {
		// remove_an_article($(this));
	}
	
}


// Préparation XML pour l'automatisation NL
// Ticket_ID => #148008147
function show_nl_xml(){
	var $ = jQuery;
	var data = $('#previewer_html');
	var titre = $('#title').val();
	var  sd = new Date($('#startdt').val());
	var st = $.datepicker.formatDate('yymmdd', new Date(sd)).toString();
	var start_dt = st+addZero(sd.getHours())+addZero(sd.getMinutes())+addZero(sd.getSeconds());
	
	var selected = $('#segement_id option:selected').text().toUpperCase().trim();
	var value_selected = $('#segement_id option:selected').val();


	if(typeof selected === 'undefined'){
		var selected = $('#segement_id option:firselected').text().toUpperCase().trim();
	}
	if(typeof value_selected === 'undefined'){
		var value_selected = $('#segement_id option:first').val();
	}

	if(site_config_js.nl_automatique_mapping != undefined && site_config_js.nl_automatique_mapping[value_selected]){
		selected = site_config_js.nl_automatique_mapping[value_selected];
	}

	var text_version = 'Voir cet email dans le navigateur - Me désinscrire\nPour être sûr(e) de recevoir nos newsletters, veuillez ajouter à votre carnet d\'adresse.\n/cdn-cgi/l/email-protection\n';
	var txt;
	var link;
	$('#previewer_html a').each(function(){
		txt = this.text.trim();
		link = this.href;
		if( link != undefined & link != '' && link.indexOf("/wp-admin") === -1 && txt != undefined && txt != '' && txt.indexOf("<img") === -1 ){
			text_version += txt+"\n";
			text_version += link+"\n";
		}
	});
	text_version += "Si vous ne souhaitez plus recevoir les newsletters édito de Auto Moto, vous pouvez vous désabonner.\nTous droits réservés © 2017 Reworld Media Factory \n16 Rue du Dôme - 92100 Boulogne Billancourt.";
	
	var reply_addr = 'relationclient@reworldmediafactory.fr';
	if(site_config_js.nl_automatique && site_config_js.nl_automatique.reply_addr){
		reply_addr = site_config_js.nl_automatique.reply_addr;
	}
	var form_addr = 'demo@emsecure.net';
	if(site_config_js.nl_automatique && site_config_js.nl_automatique.form_addr){
		form_addr = site_config_js.nl_automatique.form_addr;
	}
	var form_name = site_config_js.nl_automatique.form_name;
	var macat = site_config_js.nl_automatique.ma_category+'_'+selected;
	if(site_config_js.nl_automatique && site_config_js.nl_automatique.form_type){
		form_name = form_name+site_config_js.nl_automatique.form_type[value_selected][0];
		if(site_config_js.nl_automatique.form_type[value_selected][1] != ''){
			macat = site_config_js.nl_automatique.form_type[value_selected][1];
		}
	}


	var xml_nl = '<?xml version="1.0"?>\n';
	xml_nl += '<API>\n';
	xml_nl += '<CAMPAIGN NAME="'+titre+'" STATE="ACTIVE" FOLDERID="'+site_config_js.nl_automatique.folder_id+'" START_DT="'+start_dt+'" TAG="" DESCRIPTION="My-Description" MACATEGORY="'+macat+'" PRODUCTID="1" CLASHPLANID="0"/>\n';
	xml_nl += '<EMAILS>\n';
	xml_nl += '<EMAIL NAME="'+titre+'" FOLDERID="'+site_config_js.nl_automatique.folder_id+'" MAILDOMAINID="'+site_config_js.nl_automatique.mail_domaine_id+'" LIST_UNSUBSCRIBE="TRUE" QUEUEID="2" TAG="" MACATEGORY="'+macat+'">\n';
	xml_nl += '<TARGET LISTID="2" PRIORITY_FIELD="CREATED_DT" PRIORITY_SORTING="DESC" SEGMENTID="'+value_selected+'" CONSTRAINT="" SCOPES=""/>\n';
	xml_nl += '<CONTENT HYPERLINKS_TO_SENSORS="1">\n';
	xml_nl += '<HTML><![CDATA[<html>html_nl</html>]]></HTML>\n';
	xml_nl += '<TEXT><![CDATA['+text_version+']]></TEXT>\n';
	xml_nl += '<FROM_ADDR><![CDATA['+form_addr+']]></FROM_ADDR>\n';
	xml_nl += '<FROM_NAME><![CDATA['+form_name+']]></FROM_NAME>\n';
	xml_nl += '<TO_ADDR><![CDATA[~MAIL~]]></TO_ADDR>\n';
	xml_nl += '<TO_NAME><![CDATA[~NAME~]]></TO_NAME>\n';
	xml_nl += '<REPLY_ADDR><![CDATA['+reply_addr+']]></REPLY_ADDR>\n';
	xml_nl += '<REPLY_NAME><![CDATA['+site_config_js.nl_automatique.reply_name+']]></REPLY_NAME>\n';
	xml_nl += '<SUBJECT><![CDATA['+$('#obj_1').val()+']]></SUBJECT>\n';
	xml_nl += '</CONTENT>\n';
	xml_nl += '</EMAIL>\n'
	xml_nl += '</EMAILS>\n';
	xml_nl += '</API>\n';

	var preheader = '<!--Visually Hidden Preheader Text: BEGIN -->';
	preheader +='<div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">';
	preheader += $('#obj_2').val();
	preheader +='</div>';
	preheader += '<!--VisuallyHidden PreheaderText: END -->';

	$( data ).find("bodyrw").prepend(preheader);
	data = data.html();
	data = data.replace(/headrw/g,'head').replace(/bodyrw/g,'body');
	data = data.replace(/<!--\s+\[if/g,'<!--[if');

	//add HYPERLINK_TO_SENSORS to all href
	data = data.replace(/(<a\s+[^>]+)(>)/g, "$1 HYPERLINK_TO_SENSORS=\"TRUE\"$2");
	

	data =  data.replace(/&amp;/g, "&")
				.replace(/&lt;/g, "<")
				.replace(/&gt;/g, ">")
				.replace(/&quot;/g, '"')
				.replace(/&#039;/g, "'");

	xml_nl = xml_nl.replace(/html_nl/g, data);

	$("#previewer_xml").empty();
	$("#previewer_xml").append("<xmp>"+xml_nl+"</xmp>");


	$(".nl_tabs").removeClass('active');
	$(".xml_tab").addClass('active');

	$(".nl_tab").removeClass("active");
	$(".xml_newsletter_tab").addClass("active");
}

function send_json_cheetah(){
	var compName = $('#title').val();
	var startTime = $("#startdt").val().replace(/\//g, '-');
	var obj = $('#obj_1').val();
	check_date_nl();
	if(compName == ''){
		alert('ERREUR : le nom de la campagne n\'est pas defini !!');
		return false;
	}
	if(startTime == ''){
		alert('ERREUR : startTime n\'est pas defini !!');
		return false;
	}
	if(obj == ''){
		alert('ERREUR : l\'Objet de la Campagne n\'est pas defini !!');
		return false;
	}

	$("#nl_loading").css({'display': 'block'});

	var value_selected = $('#template_id option:selected').val();
	if(typeof value_selected === 'undefined'){
		var value_selected = $('#template_id option:first').val();
	}

	data_html = $('#previewer_html');
	if(data_html.html().search("Visually Hidden Preheader Text") < 0) {
		var preheader = '<!--Visually Hidden Preheader Text: BEGIN -->';
		preheader +='<div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">';
		preheader += $('#obj_2').val();
		preheader +='</div>';
		preheader += '<!--VisuallyHidden PreheaderText: END -->';

		$( data_html ).find("bodyrw").prepend(preheader);
	}
	data_html = data_html.html();
	data_html = data_html.replace(/headrw/g,'head').replace(/bodyrw/g,'body');
	data_html = data_html.replace(/<!--\s+\[if/g,'<!--[if');
	data_html =  data_html.replace(/&amp;/g, "&")
				.replace(/&lt;/g, "<")
				.replace(/&gt;/g, ">")
				.replace(/&quot;/g, '"')
				.replace(/&#039;/g, "'");


	data_html = '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">'+ data_html.replace(/<script(.*?)>(.*?)<\/script>/mi, " ") +'</html>'
	// Afficher le code html du caractère "espace vide"
	data_html = data_html.replace(/\u200c/g, '&zwnj;');
	// Replace "&amp;" by a simple "&"
	data_html = data_html.replace(/&amp;/g, '&');

	data_html = data_html.replace(/<tbody>/g, ""); 
	data_html = data_html.replace(/<\/tbody>/g, ""); 
		
	$.ajax({
		url : '/',
		type : 'POST',
		dataType: 'json',
		data : {
			action : 'create_campagne_chetaah_ajax',
			compName : compName,
			startTime : startTime,
			obj : obj,
			data_html : data_html,
			template_id: value_selected
		},
		success : function( response ) {
			$("#nl_loading").css({'display': 'none'});
			if (response.result == 'success') {
				$(".json_btn").attr("disabled",true);
				alert('Campagne créer avec succés!');
				$('#btns_cheetah').prepend("<strong style=\"color: #FFF;\"> l'ID de la compagne est : "+response.ID+" </strong><br />");
				var $launch_cheetah = $('#btns_cheetah .launch_cheetah');
				$launch_cheetah.removeAttr("disabled");
				$launch_cheetah.attr('data-compagnid', response.ID);
			}else{
				alert(response.result);
			}
			return false;
		},
	});

}

function launch_cheetah(){
	$("#nl_loading").css({'display': 'block'});
	$.ajax({
		url : '/',
		type : 'POST',
		dataType: 'json',
		data : {
			action : 'launch_compagne_ajax',
			compagn_ID : $('#btns_cheetah .launch_cheetah').data('compagnid'),
			compName : $('#title').val()
		},
		success : function( response ) {
			$("#nl_loading").css({'display': 'none'});
			if (response.result == 'success') {
				$('#btns_cheetah .launch_cheetah').attr("disabled",true);
				alert('Success !');
			}else{
				alert(response.result);
			}
			return false;
		},
	});
}



jQuery(document).ready(function($){		
	/**
	 * on check an articles, add or remove an articles to/from choosen_articles array JSON
	 */
	$("#startdt").datetimepicker();

	$(".xml_btn").on("click", show_nl_xml);
	$("#btns_cheetah .launch_cheetah").on("click", launch_cheetah);
	//$("#btns_cheetah .approve_cheetah").on("click", approve_cheetah);

	$(".json_btn").on("click", send_json_cheetah);

	$(".newsletter_checked_article").on('click', choose_an_article);//end of $(".newsletter_checked_article").click

	$('.nl_tabs').on('click', function(){
		var $__this = $(this);
		if($__this.hasClass('apecu')){
			$(".preview_newsletter_btn").click();
		} else {
			$(".nl_tab").removeClass("active");
			var ref = $__this.attr('ref');
			$(".nl_tab"+ref).addClass("active");
		}
	}); // end of $('.nl_tabs').on('click'

	selectedOption = $("select[name=rw_templates] option:selected");
	setMassageNl(selectedOption.data("minart"));

	$("select[name=rw_templates]").change(function(){
		selectedOption = $("select[name=rw_templates] option:selected");
		nbrMin = selectedOption.data("minart");
		setMassageNl(nbrMin);
	});

	/**
	 * click to display articles for edition
	 */
	$(".newsletter_edit_articles_btn").on("click", function() {
		var article_selector = ".edit_articles_div div.widgets-sortables";

		var nbrMin = selectedOption.data("minart");
		var articlesChoisis = $(".list_selected_articles").children().length;

		if( articlesChoisis < nbrMin ){
			alert(messageMinArt.replace("{param}",(nbrMin-articlesChoisis)));
			return false;
		}

		$(article_selector).html("");
		var count =0;
		for(key in choosen_articles) {
			var current_post = count;//choosen_articles[key]["position"];
			var article_id   = choosen_articles[key]["id"];
			var article_excerpt = '';
			var article_image = '';
			

			var article_div  = '<div class="widget" data-pos="'+count+'">';
			
			article_div += '<div class="widget-top">';
			article_div += '	<div class="widget-title-action">';
			article_div += '		<a class="widget-action hide-if-no-js" href="#available-widgets"></a>';
			article_div += '		<a class="widget-control-edit hide-if-js" href="/wp-admin/widgets.php?editwidget=text-21&amp;sidebar=footer-home&amp;key=0">';
			article_div += '			<span class="edit">Modifier</span>';
			article_div += '			<span class="add">Ajouter</span>';
			article_div += '			<span class="screen-reader-text">Texte</span>';
			article_div += '		</a>';
			article_div += '	</div><!-- .widget-title-action -->';
			article_div += '	<div class="widget-title ui-sortable-handle">';
			article_div += '		<h4><span class="in-widget-title"> ' + choosen_articles[key]['title'] + '</span></h4>';
			article_div += '	</div><!-- .widget-title -->';
			article_div += '</div><!-- .widget-top -->';

			article_div += '<div class="widget-inside" style="/*display:block;overflow:hidden;*/" >'
			article_div += '	<input type="hidden" name="article[' + current_post + '][id]" id="post_id_' + article_id + '" value="' + article_id + '" />';
			article_div += '	<input type="hidden" class="current_position" name="article[' + current_post + '][position]" id="post_position_' + article_id + '" value="' + (count++) + '" />';
			
			// title div
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_title_' + article_id + '">Title<br/>';
			article_div += '		<input type="text" name="article[' + current_post + '][title]" id="post_title_' + article_id + '"  />';
			article_div += '	</label></p>';
			// content div	
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_content_' + article_id + '">Content<br>';
			article_div += '		<textarea rows="4" name="article[' + current_post + '][excerpt]" id="post_content_' + article_id + '">'+choosen_articles[key]['excerpt']+'</textarea>';
			article_div += '	</label></p>';

			// image div	
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_image_' + article_id + '">Image<br/>';
			article_div += '		<!--<input type="file" name="article[' + current_post + '][image]" id="post_image_' + article_id + '" ><br/>-->';
			article_div += '		<button type="button" class="button upload-image" onclick="upload_image(this, '+article_id+') ">Changer l\'image</button><br/>';
			
			article_div += '		<img class="src_image" src="'+choosen_articles[key]['image']+'" id="src_image_' + article_id + '" width="200">';
			article_div += '		<input type="hidden" name="article[' + current_post + '][image]" id="post_image_' + article_id + '" value="'+choosen_articles[key]['image']+'" />';
			article_div += '		<input type="hidden" name="article[' + current_post + '][attachment_id]" id="post_attachment_id_' + article_id + '" value="'+choosen_articles[key]['attachment_id']+'" />';
	 		article_div += '		<a title="Crop Image" id="crop_' + article_id + '"  href="/wp-admin/admin-ajax.php?action=croppostthumb_ajax&image_id='+ choosen_articles[key]['attachment_id'] +'&viewmode=single&TB_iframe=1&width=753&height=305" class="thickbox button" style="margin: 5px; padding: 5px; display: inline-block; line-height: 1;">Crop Image</a>';

			// permadivnk div
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_permalink_' + article_id + '">PermaLien<br/>';
			article_div += '		<input type="text" name="article[' + current_post + '][permalink]" id="post_permalink_' + article_id + '" value="'+choosen_articles[key]['permalink']+'" />';
			article_div += '	</label></p>';

			// Tracking div
			if( site_config_js.devs.ajout_tracking_specifique_156084076 ){
				var post_tracking = choosen_articles[key]['post_tracking'] ? choosen_articles[key]['post_tracking'] : '';
				article_div += '	<p class="description description-wide">';
				article_div += '		<label for="post_permalink_' + article_id + '">Tracking<br/>';
				article_div += '		<input type="text" name="article[' + current_post + '][post_tracking]" id="post_tracking_' + article_id + '" value="'+post_tracking+'" />';
				article_div += '	</label></p>';
			}

			// category_name div
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_category_name_' + article_id + '">Category Name<br/>';
			article_div += '		<input type="text" name="article[' + current_post + '][category_name]" id="post_category_name_' + article_id + '" value="'+choosen_articles[key]['category_name']+'" />';
			article_div += '		</label></p>';

			// category_link div
			article_div += '	<p class="description description-wide">';
			article_div += '		<label for="post_category_link_' + article_id + '">Category Link<br/>';
			article_div += '		<input type="text" name="article[' + current_post + '][category_link]" id="post_category_link_' + article_id + '" value="'+choosen_articles[key]['category_link']+'" />';
			article_div += '		</label></p>';

			if(site_config_js.activate_date_article_NL) {
				// date div
				article_div += '	<p class="description description-wide">';
				article_div += '		<label for="post_date_' + article_id + '">Date<br/>';
				article_div += '		<input type="text" name="article[' + current_post + '][date]" id="post_date_' + article_id + '" value="'+choosen_articles[key]['date']+'" />';
				article_div += '		</label></p>';
			}
			article_div += '</div><!-- .widget-inside -->';
			article_div += '</widget><!-- .menu_item.menu-item-edit-inactive -->';

			$(article_selector).append(article_div);
			jQuery('#post_title_'+article_id).val(choosen_articles[key]['title']);

		} // end for
		var keys_to_get = [];
		for(key in choosen_articles) {
			
			var article_id = choosen_articles[key]["id"];
			if(articles_gotten_from_db.indexOf(article_id) == -1 ) {
				keys_to_get.push(article_id);
			}
			
		}
		$.getJSON("/wp-admin/admin-ajax.php?action=rw_retrieve_article_newsletter", 
			{post_ids:keys_to_get.join()}, 
			function(data){
				for(key in data) {
					var an_article = data;
					var article_excerpt = an_article[key]['excerpt'];
					var article_image = an_article[key]['image'];
					var article_permalink = an_article[key]['permalink'];
					var attachment_id = an_article[key]['attachment_id'];
					var article_category_name = an_article[key]['category_name'];
					var article_category_link = an_article[key]['category_link'];
					

					$('#src_image_' + key ).attr('src', article_image);
					$('#post_image_' + key ).val( article_image);
					$('#post_attachment_id_' + key ).val( attachment_id);
					$('#post_content_' + key ).text(article_excerpt);
					$('#post_permalink_' + key ).val(article_permalink);
					$('#post_category_name_' + key ).val(article_category_name);
					$('#post_category_link_' + key ).val(article_category_link);
					if(site_config_js.activate_date_article_NL) {
						var article_date = an_article[key]['date'];
						$('#post_date_' + key ).val(article_date);
					}
				}
				
			}
		);
		// add class active to article tab header 
		$(".header-tabs > li").removeClass("active");
		$(".header-tabs > li.articles").addClass("active");
		
		// hide template tab, and show articles edition tab
		$(".nl_tab").removeClass("active");
		$(".edit_articles_div").addClass("active");
		

		// event for sorting articles div
		$( "#sortable-articles" ).sortable({
	      	stop: function(event, ui) {
	      		// for each widget modify it current position on changing the name of its field
	      		$( "#sortable-articles .widget" ).each(function( index, element, array){
	      			$(element).find(".current_position:first").val(index);
	      			$(element).find("input[name*=article], textarea[name*=article]").each(function(){

		      			var name = $(this).attr("name");
		      			var new_name = setCharAt(name, 8, index);
		      			$(this).attr("name", new_name);
	      			});
	      			$(element).attr("data-pos",index);

	      		});
		    }
	    });
	   
	    // open/hide article container
	    $("#sortable-articles .widget-action").on("click", function(){
	    	var $widget_first = $(this).parents(".widget:first");
	    	if(!$widget_first.hasClass("open")){
	    		$widget_first.find(".widget-inside:first").show('slow');
	    		$widget_first.addClass("open");
	    	} else {
	    		$widget_first.find(".widget-inside:first").hide('slow');
	    		$widget_first.removeClass("open");
	    	}
	    });		
	}); // end of $(".newsletter_edit_articles_btn").on("click"

	/**
	 * preview of newsletter
	 */
	$(".preview_newsletter_btn").click(function() { 
		var formData = $('form[name=post] [name!=action]').serialize();
		$.post("/wp-admin/admin-ajax.php?action=rw_preview_newsletter", formData, 
			function(data) {
				data = data.replace(/<script(.*?)>(.*?)<\/script>/mi, " ") ;
				if($('#hide_tags_nl').is(":checked")){
					data = data.replace(/(<!--\s+TITRE POWERSPACE\s+-->([\s\S])+<!--\s+TITRE POWERSPACE\s+-->)/,'');
					data = data.replace(/(<!--\s+TITRE TABOOLA\s+-->([\s\S])+<!--\s+TITRE TABOOLA\s+-->)/,'');
					data = data.replace(/(<!--\s+8 COLONNES\s+-->([\s\S])+<!--\s+8 COLONNES\s+-->)/,'');
				}
				if($('#hide_bandeau_nl').is(":checked")){
					data = data.replace(/(<!--\s+BANDEAU\s+-->([\s\S])+<!--\s+BANDEAU\s+-->)/,'');
				}
				if( site_config_js.show_deuxieme_bandeau && $('#hide_bandeau_nl_2').is(":checked") ){
					data = data.replace(/(<!--\s+BANDEAU_2\s+-->([\s\S])+<!--\s+BANDEAU_2\s+-->)/,'');
				}

				$("#previewer_html").html(data);

					// add class active to article tab header 
	 			$(".header-tabs > li").removeClass("active");
	 			$(".header-tabs > li.apercu").addClass("active");
	 			
	 			// hide template tab, and show articles edition tab
	 			$(".nl_tab").removeClass("active");
	 			$(".preview_newsletter_tab").addClass("active");
			});
	});

	/**
	 * go to previous view
	 */
	$(".previous_btn").on("click", function() {
		var backTo=$(this).attr("back-to");
		var tab = backTo.indexOf("articles")!=-1 ? "articles":"template";
		// add class active to article tab header 
		$(".header-tabs > li").removeClass("active");
		$(".header-tabs > li."+tab).addClass("active");
		
		// hide template tab, and show articles edition tab
		$(".nl_tab").removeClass("active");
		$("."+backTo).addClass("active");
	});

	$("#newsletter_articles_autocomplete").show();
  	$("#newsletter_articles_autocomplete").searchRw({
		data:{
			exclude: f_articles_to_exclude(),
			planified_posts : site_config_js.nl_planified_posts
  		},
  		func:function(e) {	
			var a = $(e);
			var id = a.attr('data-id');
			if(id) {
				var title = a.find('.item-title').html();
				var new_line = '<li id=line_'+id+'>'+remove_btn_html+' <input style="display:none;" class="newsletter_checked_article" type="checkbox" value="'+id+'" />';
				new_line += '<span class="newsletter_article_title">'+ title +'</span></li>';

				// var  selected_html = $('.list_selected_articles').html();
				// selected_html += new_line;
				$('.list_selected_articles').append(new_line);
				// bind remove action to remove button
				remove_selected_article() ;

				a.remove();

				var article = {};
				article["position"] = ++position;
				article ['id'] = id;
				article['title'] = title; //JSON.stringify(title);
  				if(site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
					choosen_articles.push(article);
  				}else{
					choosen_articles[id] = article;
  				}


				new_articles_added.push(id);
				
			}
			return false;
		}

	});

	//initZeroCopyClipboard();
	copyIntoClipboard();

  	$(".selected_articles_area li").each( function(){
  		var $_this = $(this);
  		var $_checked_article = $_this.find('.newsletter_checked_article');

  		$_this.attr("checked","checked");
  		if(!site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
  			select_an_article($_checked_article);
  		}

		articles_gotten_from_db.push($_checked_article.val());
		
  	});
  	if(!site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
  		init_choosen_articles();
  	}

  	$('.selected_articles_area a.remove').click(function(){
		removeSelectedArticle($(this));
	});

	$('.popular_articles li').click(function() {
		var a = $(this);
		var id = a.attr('data-id');
		if(id) {
			var title = a.find('.item-title').html();
			var new_line = '<li id=line_'+id+'>'+remove_btn_html+' <input style="display:none;" class="newsletter_checked_article" type="checkbox" value="'+id+'" />';
			new_line += '<span class="newsletter_article_title">'+ title +'</span></li>';
			$('.list_selected_articles').append(new_line);
			remove_selected_article() ;
			a.remove();
			var article = {};
			article["position"] = ++position;
			article ['id'] = id;
			article['title'] = title; //JSON.stringify(title);
			if(site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
				choosen_articles.push(article);
			}else{
				choosen_articles[id] = article;
			}
			new_articles_added.push(id);
		}
		return false;
	});

	$("#selligent-send-btn").click(function(){
		var string_xml = $('#previewer_xml xmp').text();
		var title = $('#title').val();
		check_date_nl();

		$.ajax({
			url : '/',
			type : 'POST',
			dataType: 'json',
			data : {
				action : 'ajax_selligent_service',
				xml : string_xml,
				title : title
			},
			success : function( response ) {
				if (response.error == 'false') {   
				    alert('Campagne créer avec succés!');
				}else{
					alert('Erreur est surevenu lors de la création de la campagne');
				}
				return false;
			},
		});

	});
});


function check_date_nl(){
	var  sd = new Date($('#startdt').val());
	var date_send= $.datepicker.formatDate('yymmdd', new Date(sd)).toString();
	var current_date = $.datepicker.formatDate('yymmdd', new Date()).toString();
	if (date_send == current_date && !confirm("Attention, le message est programmé pour un envoi ce jour, êtes-vous sûr de vouloir continuer ?")){
		$('a','.nl_tabs.articles').click();
		$('#startdt' ).focus();
		return false;
	}
}
function remove_selected_article() {
	// bind remove action to remove button
	var $ = jQuery;
	$('.list_selected_articles a.remove').unbind('click');
	$('.list_selected_articles a.remove').bind('click', function(){
		removeSelectedArticle($(this));
	});
}
/**
 * init ZeroCopyClipboard plugin
 */
function initZeroCopyClipboard(){
	var $ = jQuery;
	ZeroClipboard.config( { swfPath: ZeroClipboard_ini_url } );
	var client = new ZeroClipboard( $(".copy-nl-btn"));

	client.on( "ready", function( readyEvent ) { 

		client.on( "aftercopy", function( event ) { 
		    alert("Le contenu est copié"); 
		 } );
	} );
	client.on( "copy", function (event) {
	  var clipboard = event.clipboardData; 
	  var newhtml = $("#previewer_html").html().replace(/headrw/g,'head').replace(/bodyrw/g,'body');
	  newhtml = newhtml.replace(/<!--\s+\[if/g,'<!--[if');

	  client.setText( '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">'+newhtml.replace(/<script(.*?)>(.*?)<\/script>/mi, " ") +'</html>' );

	});
}
/**
 * Copy newsletter html into the clipboard
 */
function copyIntoClipboard(){ 
	var $ = jQuery;  
	var $copyBtn = $(".copy-nl-btn");
	$copyBtn.unbind().on('click', function() { 
		var $input = document.createElement("input");
		$input.setAttribute("style", "display:block");
		var newhtml = $("#previewer_html").html().replace(/headrw/g,'head').replace(/bodyrw/g,'body');
		newhtml = newhtml.replace(/&#091;/g,'[').replace(/&#093;/g,']');
		// Afficher le code html du caractère "espace vide"
		newhtml = newhtml.replace(/\u200c/g, '&zwnj;');
		// Replace "&amp;" by a simple "&"
		newhtml = newhtml.replace(/&amp;/g, '&');
		newhtml = newhtml.replace(/<script.*>.*<\/script>/im, " ");
		newhtml = newhtml.replace(/<!--\s+\[if/g,'<!--[if');

		newhtml = newhtml.replace(/<tbody>/g, ""); 
		newhtml = newhtml.replace(/<\/tbody>/g, ""); 
		
		$input.setAttribute("value", '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">'+newhtml.replace(/<script(.*?)>(.*?)<\/script>/mi, " ") +'</html>');
		document.body.appendChild($input);
		$input.select(); 

		var succeeded;
		try { 
			succeeded = document.execCommand("copy");
			document.body.removeChild($input);

		} catch (e) {
			succeeded = false;
		}
		if (succeeded) { 
		    alert("Le contenu est copié");
		}
	}); 
}

/**
 * add an article details to choosen_articles array
 */
function select_an_article(_this) {
	var article = {};
	var $=jQuery;
	var $__this =$(_this); 
	var $_parent = $__this.parent();
	article["position"] = ++position;
	article ['id'] = $__this.val();
	article['title'] = $_parent.find(".newsletter_article_title").text();
	// article['title'] = eval(article['title']);

	article['excerpt'] = $_parent.find(".newsletter_article_excerpt").text();
	// article['excerpt'] = eval(article['excerpt']);

	article['permalink'] = $_parent.find(".newsletter_article_permalink:first").val();
	article['image'] = $_parent.find(".newsletter_article_image:first").val();
	article['category'] = $_parent.find(".newsletter_article_category:first").val();
	if(site_config_js.activate_date_article_NL)
		article['post_date'] = $_parent.find(".newsletter_article_post_date:first").val();
 	if(site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
		choosen_articles.push(article);

 	}else{
		choosen_articles[$__this.val()] = article;
 	}

	__articles_to_exclude.push(article['id']);
	$('#search-field-gallery').attr('data-exclude',__articles_to_exclude.join());
}
/**
 * remove an article from choosen_articles array
 */
function remove_an_article(_this) {
	if( jQuery(_this).val() in choosen_articles) {
		delete choosen_articles[jQuery(_this).val()];	 
	}
}
/**
 * replace a character on string
 */
function setCharAt(str,index,chr) {
    if(index > str.length-1) return str;
    return str.substr(0,index) + chr + str.substr(index+1);
}


function removeSelectedArticle(_this) {
	var $ = jQuery;
	var $__this = $(_this);
	var article_id = $__this.parent().find('.newsletter_checked_article').val();
	var index_article = -1;
	
 	
 	if(site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
		for(var i= 0; i< choosen_articles.length; i++){
			if(choosen_articles[i]['id'] == article_id){
				index_article = i ;
				break;
			}
		}
 	}else{
		var index_article = article_id;
 	}

	if( index_article in choosen_articles) {
		if(site_config_js.devs.network_module_nl_cms_enregistrement_nl_143045745){
			choosen_articles.splice(index_article,1);
		}else{
			delete choosen_articles[index_article];
		}

		for(var i= 0; i< __articles_to_exclude.length; i++){
			if(__articles_to_exclude[i] == article_id){
				__articles_to_exclude.splice(i,1);
				break;
			}
		}
		$('#search-field-gallery').attr('data-exclude',__articles_to_exclude.join());
		
		$__this.parent().find(".newsletter_checked_article").show();
		var selected_article = $__this.parent().html();
		selected_article = selected_article.replace(remove_btn_html,'');
		$html = '<li id=line_'+article_id+'>'+selected_article+'</li>';
		$(".list_articles ul").append($html);
		
		$("#line_"+article_id+" .newsletter_checked_article").on('click', choose_an_article);
		$("#line_"+article_id+" .newsletter_checked_article").removeAttr('checked'); 
	}



		
		$__this.parent().remove();

	return false;
}


function init_choosen_articles() {
	for(i in ex_articlesjs) {
		choosen_articles[ex_articlesjs[i]['id']] = ex_articlesjs[i];
	}
}
