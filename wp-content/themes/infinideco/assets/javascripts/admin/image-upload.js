jQuery( document ).ready( function( $ ) {
     // upload ***SINGLE*** image
	$( '.mx_upload_image' ).on( 'click', function( e ) {
        let mx_upload_button = $(this);
		e.preventDefault();
        var frame;

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media.frames.customBackground = wp.media({
            //modal title
			title: 'choisir Une Image',

			library: {
				type: 'image'
			},

			button: {

				text: 'Upload'
			},

			multiple: false
		});
        frame.on( 'select', function() {

			var attachment = frame.state().get('selection').first();
            // and show the image's data
			var image_id = attachment.id;

			var image_url = attachment.attributes.url;
             // pace an id
			mx_upload_button.parent().find( '.mx_upload_image_save' ).val( image_id );

			// show an image
			mx_upload_button.parent().find( '.mx_upload_image_show' ).attr( 'src', image_url );
			mx_upload_button.parent().find( '.mx_upload_image_show' ).show();
			// show "remove button"
			mx_upload_button.parent().find( '.mx_upload_image_remove' ).show();
			// hide "upload" button
			mx_upload_button.hide();
		} );

		frame.open();
    })
    // remove image
	$( '.mx_upload_image_remove' ).on( 'click', function( e ) {
		var remove_button = $( this );
		e.preventDefault();
		// remove an id
		remove_button.parent().find( '.mx_upload_image_save' ).val( '' );

		// hide an image
		remove_button.parent().find( '.mx_upload_image_show' ).attr( 'src', '' );
		remove_button.parent().find( '.mx_upload_image_show' ).hide();

		// show "Upload button"
		remove_button.parent().find( '.mx_upload_image' ).show();

		// hide "remove" button
		remove_button.hide();

	} );

	// **GALLERY** script to upload multiple images
	$('.upload_gallery_button').click(function(event){
        var current_gallery = $( this ).closest( 'div #gallery' );
        if ( event.currentTarget.id === 'clear-gallery' ) {
            //remove value from input
            current_gallery.find( '.gallery_values' ).val( '' ).trigger( 'change' );
            //remove preview images
            current_gallery.find( '.gallery-screenshot' ).html( '' );
            return;
        }
        // Make sure the media gallery API exists
        if ( typeof wp === 'undefined' || !wp.media || !wp.media.gallery ) {
            return;
        }
        event.preventDefault();
        // Activate the media editor
        var val = current_gallery.find( '.gallery_values' ).val();
        var final;
        if ( !val ) {
            final = '[gallery ids="0"]';
        } else {
            final = '[gallery ids="' + val + '"]';
        }
        var frame = wp.media.gallery.edit( final );
        frame.state( 'gallery-edit' ).on(
            'update', function( selection ) {
                //clear screenshot div so we can append new selected images
                current_gallery.find( '.gallery-screenshot' ).html( '' );
                var element, preview_html = '', preview_img;
                var ids = selection.models.map(
                    function( e ) {
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
                        preview_html = "<div class='screen-thumb' style='display: inline-block; border: 1px solid #ccc; margin: 0 10px;'><img src='" + preview_img + "'/></div>";
                        current_gallery.find( '.gallery-screenshot' ).append( preview_html );
                        return e.id;
                    }
                );
                current_gallery.find( '.gallery_values' ).val( ids.join( ',' ) ).trigger( 'change' );
            }
        );
        return false;
    });
});
