<?php
do_action('init_template_single_article');
$seo_microdata_post = apply_filters('microdata_post',true); 
$google_news = get_post_meta(get_the_ID(), 'google_news', true);
$sharedcount = get_single_sharedcount() ;

global  $is_live_content ;
$is_live_content = true;
$datas_seo = RW_Post::get_datas_seo() ;
$schema = ($google_news)? 'NewsArticle':'Article';
?>

	<?php do_action('just_after_post_v2'); ?>	

	<article id="post-<?php the_ID(); ?>" class="item">
        <?php
        if($seo_microdata_post){
        	?>
            <div itemscope itemtype="http://schema.org/<?php echo $schema; ?>">
            <?php
        }
		?>
	  	<div id='top_intro_article'>
	   		<?php do_action('top_intro_article'); ?>
	    </div>
		<?php
		do_action('seo_single', $datas_seo);

		do_action('after_visu_diapo',get_the_ID());

		$content = apply_filters( 'the_content', get_the_content() );
		$content = str_replace( ']]>', ']]&gt;', $content );
	    $content = apply_filters( 'the_live_content', $content);
	    $is_live_content = false;
		$check_cont = str_replace( '<div class="hmutinread"></div>', '', $content );
	    $check_cont = trim($check_cont);
	    $item_prop = '';
	    if($seo_microdata_post && $check_cont){
	        $item_prop='itemprop="articleBody"';
	    }
	    
	    
		?>
	    <div class="article-content thecontent" <?php echo $item_prop;?> >
	        <?php do_action('after_thecontent'); ?>
	        <div class="article_body_content">
		        <?php echo $content; ?>
	        </div>
	        <?php do_action('just_after_thecontent'); ?>
	    </div> 
		<?php
		do_action('seo_single_after_article_body', $datas_seo);

		?> 
    </article>
		
    <?php do_action('after_single_article');?>
	<div class='inboard' ></div>