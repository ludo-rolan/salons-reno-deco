<div class="modal popup" id="mondial_popup" tabindex="-1" role="dialog" aria-labelledby="mondial_popup">
	<div class="modal-dialog modal-search-container" role="document">
		<div class="modal-content">
			<div class="modal-body text-center popup_body_nl"  >
                <div data-dismiss="modal" class="dismiss_btn" >x</div>
				<img height="90" src="<?php echo STYLESHEET_DIR_URI.'/assets/images-v3/logo-black.svg' ?>" />
                <p class="subtitle"> <?php _e("Inscrivez-vous à la", REWORLDMEDIA_TERMS);?> </p>
                <h2 class="title"> <?php _e("Newsletter", REWORLDMEDIA_TERMS)?> </h2>
                <p class="subtitle">
                    <!--du Mondial de l’Auto,-->
                    <?php _e("du Mondial de l’Auto,", REWORLDMEDIA_TERMS); ?>  
                    <br/>
                   <!-- pour ne rien manquer du salon :-->
                    <?php _e(" pour ne rien manquer du salon :", REWORLDMEDIA_TERMS); ?>  
                </p>
                <div class="nl_footer_content">
                    <div class="nl_footer_text">
                    <?php _e("Hopscotch Congrès traite vos données pour vous permettre de recevoir la newsletter. 
        Pour en savoir plus sur la gestion de vos données personnelles et pour exercer vos droits, 
        reportez-vous à la", REWORLDMEDIA_TERMS); ?>                    <a href="/politique-de-protection-des-donnees" style="color:black">politique de protection des données</a>
                    </div>
                    <form class="nl_footer_form" action="/inscription-newsletter" style="border:1px solid #000">
                        <input name="email_newsletter" required="required" type="email" placeholder="email">
                        <input id="submit_nl" class="submit-inline" type="submit" value=" <?php _e('Envoyer', REWORLDMEDIA_TERMS)?>">
                    </form>
                </div>
                
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
   
    $(document).ready(function () {
        function show_popup(){
            if(!$.cookie('poupshown')){
                $('#mondial_popup').modal({
                    show:true
                });
                $.cookie('poupshown','true');
            }
        }
        var clicks_count = parseInt($.cookie('clicks_count')) || 0;
        if(clicks_count >= 3){
            clicks_count++;
            $.cookie('clicks_count',clicks_count);
            show_popup();
        }
        // add click count every time uset clicks on the page
        $(document).on('click', function () {
            if(!$.cookie('clicks_count')){
                $.cookie('clicks_count', 0);
            }
            var clicks_count = parseInt($.cookie('clicks_count')) + 1;
            $.cookie('clicks_count', clicks_count);
        });
        setTimeout(function () {
            show_popup();
        }, 10000);   
    
    });
</script>