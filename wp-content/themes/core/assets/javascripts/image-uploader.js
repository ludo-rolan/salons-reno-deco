/**
 * show image uploader pour les newsletter
 */ 
function upload_image( src, target, labels) {
	labels = {};
	labels['title' ] = 'Choisir une image';
	labels['button-label' ] = 'Ajouter l\'image';
		// e.preventDefault();
	var $el = jQuery(src).parent();
	var uploader = wp.media ({
		title    : labels['title'],
		button   : {
			text : labels['button-label']
		},
		editing  :    true,
		// state   :   'gallery-edit',
		multiple : false,
	})
	.on('select', function(){
		
		var selected = uploader.state().get('selection');
		var attach = selected.first().toJSON();
		var src_img = '#post_image_' +target;
		var attach_id = '#post_attachment_id_' +target;
		var url_input = '#post_permalink_' + target;
		var bn_crop = '#crop_' + target;
		jQuery(src_img , $el).val(attach.url);
		jQuery(attach_id , $el).val(attach.id);

		jQuery('img' , $el).attr('src', attach.url);
		jQuery(bn_crop , $el).attr('href', '/wp-admin/admin-ajax.php?action=croppostthumb_ajax&image_id='+ attach.id +'&viewmode=single&TB_iframe=1&width=753&height=305');

		// console.log(attach.url);
	})
	.open();
	var rand  = Math.random(5);
	// uploader.editor.open( "unique_id_here_"+rand );
}
/**
 * generic upload_image description]
 * @param  {obj} src button 
 * @param  {string} trg selector
 */
function gen_upload_image( src, trg, select_fun) {
	labels = {};
	var $source = jQuery(src);
	var $target = jQuery(trg);

	labels['title' ] = 'Choisir une image';
	labels['button-label' ] = 'selectionner l\'image';
	var title = $source.data('lbl-title');
	var btn_label = $source.data('lbl-btn');
	if(title!=null && title != ""){
		labels['title' ] = title;
	}
	if(btn_label!=null && btn_label != ""){
		labels['button-label' ] == btn_label;
	}

	var $el = $source.parent();
	var uploader = wp.media ({
		title    : labels['title'],
		button   : {
			text : labels['button-label']
		},
		editing  :    true,
		// state   :   'gallery-edit',
		multiple : false,
	})
	.on('select', function(){
		select_fun(uploader);
	})
	.open();
	// var rand  = Math.random(5);
	// uploader.editor.open( "unique_id_here_"+rand );
}
