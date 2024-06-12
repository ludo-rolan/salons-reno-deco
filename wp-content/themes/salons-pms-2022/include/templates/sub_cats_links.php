<?php 
$hp_sub_cats = get_data_from_cache('hp_sub_cats_'.$current_cat->term_id, 'home_rubrique', 60*60*24, function() use( $current_cat ) {
	return get_categories( array('parent' => $current_cat->term_id, 'hide_empty' => 0) );
});

if( !empty($hp_sub_cats)){ 
?>
	<ul class="sub-categories list-inline list-unstyled">
		<?php foreach ($hp_sub_cats as $sub_cat) { ?>
			<li class="sub-categories-item"><a href="<?php echo get_term_link( $sub_cat, 'category' ); ?>"><?php echo $sub_cat->name ?></a></li>
		<?php } ?>
	</ul>
<?php 
}