<div class="header-visuel">
	<div class="container header-visuel-container">
		<div class="media header-visuel-content">
			<?php if( !empty($visual) ){ ?>
				<div class="media-left media-middle">
					<a href="<?php echo $cta_link; ?>">
						<img class="media-object" src="<?php echo $visual; ?>" alt="<?php echo $post->title; ?>">
					</a>
				</div>
			<?php } ?>
			<div class="media-body media-middle">
				<?php if( !empty($subtitle) ) { ?>
					<div class="header-visuel-text"><?php echo $subtitle; ?></div>
				<?php } ?>
				<?php if( !empty($date) ) { ?>
						<div class="header-visuel-date"><?php echo $date; ?></div>
				<?php } ?>
				<?php if( !empty($title) ) { ?>
					<div class="header-visuel-title"><?php echo $title; ?></div>
				<?php } ?>
				<?php if( is_singular('exposant') ){ ?>
					<button class="btn btn-primary" id="scroll_to_contact">Contact</button>
				<?php } else { ?>
					<a class="btn btn-primary" href="<?php echo $cta_link; ?>">Découvrir</a>
				<?php } ?>
			</div>
		</div>

	</div>
</div>