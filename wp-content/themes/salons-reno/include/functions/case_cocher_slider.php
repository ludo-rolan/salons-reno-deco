<?php 

add_action('post_submitbox_misc_actions', 'createCustomField');
add_action('save_post', 'saveCustomField');
function createCustomField()
{
    $post_id = get_the_ID();
  
    if (get_post_type($post_id) == 'post' || get_post_type($post_id) == 'exposant' ) {
     
  
    $value = get_post_meta($post_id, 'reno_slider_locking', true);
    wp_nonce_field('my_custom_nonce_'.$post_id, 'my_custom_nonce');
?>

    <div class="misc-pub-section misc-pub-section-last">
        <label><input type="checkbox" value="1" <?php checked($value, true, true); ?> name="reno_slider_locking" /><?php _e('Reno slider locking', 'pmg'); ?></label>
    </div>
    <?php
     }
}
function saveCustomField($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (
        !isset($_POST['my_custom_nonce']) ||
        !wp_verify_nonce($_POST['my_custom_nonce'], 'my_custom_nonce_'.$post_id)
    ) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['reno_slider_locking'])) {
        update_post_meta($post_id, 'reno_slider_locking', $_POST['reno_slider_locking']);
    } else {
        delete_post_meta($post_id, 'reno_slider_locking');
    }
}
