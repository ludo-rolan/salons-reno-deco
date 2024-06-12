<?php
//tester si le plugin wpml est activer
if ( function_exists('icl_object_id') ) {
    ?>
    <div class="lang-switch">
	<?php $langs = apply_filters('wpml_active_languages', NULL); ?>
	<?php if (!empty($langs)) : ?>
		<div class="dropdown">
			<button class="lang_btn btn dropdown-toggle pl-2 pt-0 pb-0" type="button" id="dropdownLangSwitcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-uppercase"><?php echo ICL_LANGUAGE_CODE; ?></span>    
                <i class="fa fa-angle-down"></i>
			</button>
			<div class="lang_menu dropdown-menu" aria-labelledby="dropdownLangSwitcher">
				<?php foreach ($langs as $lang) : ?>
						<a class="lang_menu_item dropdown-item text-uppercase <?php if (ICL_LANGUAGE_CODE === $lang['language_code']) : ?>active<?php endif; ?>" href="<?php echo $lang['url']; ?>"><?php echo $lang['language_code']; ?></a>
				<?php endforeach;	?>
			<?php endif; ?>
			</div>
		</div>
</div>
<?php
}

