<?php 
if( is_dev('integration_discover_india_140316711') ){
	$content .= '<div class="inter-site-wrapper">';
}
$content .= '<h2 class="default-title"><span class = "block-title">'.$_block_title .'</span></h2>
<div class="items-posts items-posts-related inter-site">
<div class="row">';
foreach ($posts as $p) {
	$_permalink = get_the_permalink( $p );
	$_thumbnail = get_the_post_thumbnail_url( $p ,'la_une_nl_size_dimension');
	$_title = $p->post_title; 
	$content.='<div class="col-xs-12 col-sm-4 item-post">
	<div class="thumbnail-inter">
	<a href="'.$_permalink.'" class="thumbnail" target="_blank" >';
	$content.='<img class="attachement-rw_medium tracking-img" src="'.$_thumbnail.'" alt="'.$_title.  '" title="'.$_title.'"  />';
	$content.='</a>
	<a href="'.$_permalink.'" target="_blank"  class="crp_title tracking-link" >'.$_title.'</a>
	</div>
	</div>';
}
$content.='</div>
</div>';
if( is_dev('integration_discover_india_140316711') ){
	$content.='</div>';
}
?>