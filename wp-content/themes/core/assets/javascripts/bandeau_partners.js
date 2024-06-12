$ = jQuery;
jQuery(document).ready(function($) {
	var mediaUploader;
	$(document).on('click','.add_logo', function(e) {
      e.preventDefault();
      var id = $(this).data("id");
      console.log(id);
      if (mediaUploader) {
        mediaUploader.open();
        return;
      }
      mediaUploader = wp.media.frames.file_frame = wp.media({
        title: 'Choisir une image',
        button: {
          text: 'Choisir'
        },
        multiple: false
      });
      mediaUploader.on('select', function() {
        var attachement = mediaUploader
          .state()
          .get('selection')
          .first()
          .toJSON();
          console.log(attachement.sizes);
          var img = attachement.sizes.full;
        $('#partner_img_'+id).val(img.url);
        $('#partner_img_preview_'+id).attr('src', img.url).removeClass('hidden');
        mediaUploader = null;
      });
      mediaUploader.open();
    });
});