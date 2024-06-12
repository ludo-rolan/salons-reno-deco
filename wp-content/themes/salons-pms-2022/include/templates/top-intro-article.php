<?php
global $has_gallery, $post;

if( !wp_is_mobile() ): 
    do_action('single_after_title');

    $time_post_modified = strtotime( $post->post_modified) ;
    $time_post_date = strtotime( $post->post_date) ;
    if( $time_post_modified < $time_post_date){
        $date = get_the_date();
    }else{
        $date = get_the_modified_date();
    }
endif; ?>

<div class="post_signature">
    <div class="post_signature_item">
        <?php
            $content = apply_filters( 'custom_posts_datetime', get_the_date() ); 
            echo $content; 
        ?>                 
    </div>
    <?php if( !wp_is_mobile() ): ?>
        <div class="post_signature_item">
            <?php echo __( 'mis à jour le ' ) . $date ; ?>
        </div>
    <?php endif ?>
</div>

<?php
do_action('before_top_img_single');

if( rw_post::page_has_gallery() ){
    do_action('show_gallery');
}else{
    $is_thumb_featured = get_post_meta($post->ID, 'is_thumb_featured', true);
    if( apply_filters('is_thumb_featured', $is_thumb_featured) ){ 
        $attachment = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');?>
        <span data-href="<?php echo $attachment[0]; ?>"  alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>">				
            <?php 
                $attrs = array("class" => "img-responsive");
                if( !is_dev('seo_correction_micro_donnees_articles_111394476') ){
                    $attrs["itemprop"] = "image";
                }
                echo get_the_post_thumbnail( $post->ID, "rw_gallery_full", $attrs ); 
            ?>
        </span>	
    <?php 
    }
}
if( has_excerpt() ){
    the_excerpt();
}

