<?php
$footer_info = isset(get_param_global('cheetah_nl')['footer_nl_info']) ? get_param_global('cheetah_nl')['footer_nl_info'] : false ;

?>
<div id="footer_accept_newsletter" class="<?php echo isset($footer_info['class']) ? $footer_info['class'] :''; ?> hidden">
	<button type="button" id="close_bar" class="close">
		<span>&times;</span>
	</button>

	<div class="content_nl">
		<div class="form_text">
			<?php echo isset($footer_info['msg']) ? $footer_info['msg'] :'Inscrivez-vous gratuitement aux newsletters'; ?>
		</div>
		<form method="post" id="form_nl_footer" name="form_BE_box" action="">
			<input type="email" required placeholder="Votre adresse mail" id="footer_email" name="mail" />
			<button id="subscribe_newsletter" class="btn" type="submit">M'inscrire</button>
		</form>
		<button class="btn btn-tkns" type="submit"> Merci</button>
	</div>
</div>