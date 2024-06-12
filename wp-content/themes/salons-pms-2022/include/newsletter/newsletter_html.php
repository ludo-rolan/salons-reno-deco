<div class="nl-body jlm">
	<style>
		.form-group.checkbox{
			position: relative;
		}
		.form-group.checkbox input[type="checkbox"]{
			position: absolute;
			opacity: 0;
			cursor: pointer;
			height: 0;
			width: 0;
		}
		.form-group.checkbox {
			display: block;
			position: relative;
			padding-left: 35px;
			margin-bottom: 12px;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		.form-group.checkbox label.checkbox-label{
			font-size: 12px;
			font-weight: 900;
		}
		/* Create a custom checkbox */
		.checkmark {
			position: absolute;
			top: 4px;
			left: 0;
			height: 25px;
			width: 25px;
			border: 2px solid #000;
			background-color: #eee;
		}

		/* On mouse-over, add a grey background color */
		.form-group.checkbox:hover input ~ .checkmark {
			background-color: #ccc;
		}

		/* When the checkbox is checked, add a blue background */
		.form-group.checkbox input:checked ~ .checkmark {
			background-color: #000;
		}

		/* Create the checkmark/indicator (hidden when not checked) */
		.checkmark:after {
			content: "";
			position: absolute;
			display: none;
		}

		/* Show the checkmark when checked */
		.form-group.checkbox input:checked ~ .checkmark:after {
			display: block;
		}

		/* Style the checkmark/indicator */
		.form-group.checkbox .checkmark:after {
			left: 9px;
			top: 5px;
			width: 5px;
			height: 10px;
			border: solid white;
			border-width: 0 3px 3px 0;
			-webkit-transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			transform: rotate(45deg);
		}

	</style>

	<div id="nl_body">
		<h2 class="text-center page-title"><?php _e("Inscription à la newsletter", REWORLDMEDIA_TERMS);?></h2>
		<h3 class="text-center page-sub-title"><?php _e("Inscrivez-vous pour recevoir le meilleur du Mondial de l'Auto", REWORLDMEDIA_TERMS); ?></h3>
		<form action="" method="post" class="form-horizontal" id="signupForm_nl">
			<div class="offers">
				<!-- <div class="offers_item">
					<input type="checkbox" value="pms_optin_edito" class="offer_checkbox" id="choice-1" class="offer_checkbox" name="OPTIN_CHECKBOX" />
					<label for="choice-1"></label>
					<span class="check-box"></span>
					<strong>La newsletter officielle du Mondial de l’Auto :</strong> retrouvez nos dernière actualités !
					<ul class="list-unstyled list-inline pull-right offers_const">
						<li class="offers_frequancy">2j/7</li>
						<li class="offers_time">9h</li>
					</ul>
				</div>

				<div class="offers_item">
					<input type="checkbox" value="pms_optin_part" id="choice-3" class="offer_checkbox" class="offer_checkbox" name="PART_CHECKBOX" />
					<label for="choice-3"></label>
					<span class="check-box"></span>
					<strong>Recevoir les bons plans des partenaires du Mondial de l’Auto <span class="double_asterisk"></span></strong>
				</div> -->

			</div>
			<div class="row sign-form">
				<!-- <h3 class="text-center page-title">Je m'inscris gratuitement</h3> -->
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="row">
						<!-- <div class="col-xs-12">
							<label for="exampleInputEmail1" class="block">Civilité*</label>

							<input type="radio" value="1" id="civilite_me" name="civilite_nl" />
							<label class="radio-inline" for="civilite_me">
								<span></span>Mr
							</label>

							<input type="radio" value="2" id="civilite_mme" value="mme" name="civilite_nl" checked>
							<label class="radio-inline" for="civilite_mme">
								<span></span>Mme
							</label>

						</div> -->
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<label for="prenom"><?php _e("Prénom", REWORLDMEDIA_TERMS);?>*</label>
								<input type="text" required class="form-control" placeholder="<?php _e("Prénom", REWORLDMEDIA_TERMS);?>*" id="prenom_nl" name="FIRSTNAME"/>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<label for="nom"><?php _e("Nom", REWORLDMEDIA_TERMS);?>*</label>
								<input type="text" required class="form-control" placeholder="<?php _e("Nom", REWORLDMEDIA_TERMS);?>*" id="nom_nl" name="NAME"/>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label for="email"><?php _e("Adresse mail", REWORLDMEDIA_TERMS);?>*</label>
								<input type="email" required name="MAIL" checked="checked" <?php echo (isset($_GET["email_newsletter"]) && $_GET["email_newsletter"]) ? "value=".$_GET["email_newsletter"] : ''; ?> class="form-control" placeholder="<?php _e("Adresse mail", REWORLDMEDIA_TERMS);?>" id="email_nl"/>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group checkbox offers">
								<label class="checkbox-label" for="choice-1"><?php _e("La newsletter officielle du Mondial de l'Auto : ", REWORLDMEDIA_TERMS);?> <small><?php _e("retrouvez nos dernières actualités", REWORLDMEDIA_TERMS); ?></small> 
									<input type="checkbox" value="pms_optin_edito"  id="choice-1" class="offer_checkbox category-optin" name="OPTIN_CHECKBOX" />
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group checkbox offers">
								<label class="checkbox-label" for="choice-3"><?php _e("Recevoir les bons plans des exposants et partenaires du Mondial de L'Auto", REWORLDMEDIA_TERMS); ?>
									<input type="checkbox" value="pms_optin_part" id="choice-3"  class="offer_checkbox category-optin" name="PART_CHECKBOX" />
									<span class="checkmark"></span>
								</label>
								
							</div>
						</div>
						<div class="col-xs-12 submit-data">
							<div class="form-group checkbox offers">
								<button type="submit" class="btn btn-default" name="SUBMITBTN" > <?php _e("Je m'inscris", REWORLDMEDIA_TERMS); ?> </button>
								<input type="button" style="opacity:0;width:0;height:0;padding:0;" name="CATEGORY_CHECKBOX_TEST" />
								<p class="help-block">* <?php _e("tous les champs sont obligatoires", REWORLDMEDIA_TERMS); ?></p>
							</div>						
						</div>
					</div>
				</div>
			</div>
			<!-- <label tabindex="0" class="radio" for="193_1">Oui</label> -->
		</form>
		<div class="nl-feature">
			<h2 class="text-center page-title"><?php _e("Nos engagements", REWORLDMEDIA_TERMS); ?></h2>
			<div class="row">
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="book-icon"></span>
					<?php _e("Vous offrir gratuitement nos contenus exclusifs", REWORLDMEDIA_TERMS); ?>
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="folder-icon"></span>
					<?php _e("Vous permettre de mieux préparer votre visite au salon", REWORLDMEDIA_TERMS); ?>
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="msg-icon"></span>
					<?php _e("Vous accompagner tout au long de votre parcours", REWORLDMEDIA_TERMS); ?>
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="lock-icon"></span>
					<?php _e("Respecter vos données personnelles", REWORLDMEDIA_TERMS); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="confirmation-new" style="display: none">
		<h2 class="text-center page-title"><?php _e("Merci beaucoup !", REWORLDMEDIA_TERMS); ?> !</h2>
		<h3 class="text-center page-sub-title"><?php _e("Votre inscription a bien été prise en compte.", REWORLDMEDIA_TERMS); ?>
		<br /><br><?php _e("Vous recevrez très prochainement les dernières nouveautés des constructeurs automobiles, les actualités inédites du monde de l’auto-mobilité ainsi que 
		les annonces officielles du Mondial de l’Auto.", REWORLDMEDIA_TERMS); ?>
		<br><br>
		<?php _e("À très vite au Mondial de l’Auto 2022", REWORLDMEDIA_TERMS); ?> !
		</h3>
	</div>

	<div id="confirmation-old" style="display: none">
		<h2 class="text-center page-title"><?php _e("Merci ", REWORLDMEDIA_TERMS); ?>!</h2>
		<h3 class="text-center page-sub-title"><?php _e("Nous avons bien pris en compte votre demande pour l'adresse MAIL_NL", REWORLDMEDIA_TERMS); ?><br>
		<?php _e("Vous venez de recevoir un ", REWORLDMEDIA_TERMS); ?><strong><?php _e("mail de confirmation d'inscription", REWORLDMEDIA_TERMS); ?></strong> 
		(<?php _e("n'oubliez pas de regarder dans vos courriers indésirables", REWORLDMEDIA_TERMS); ?>).<br>
			<br>
			<?php _e("À très vite sur Mondial Auto Paris !", REWORLDMEDIA_TERMS); ?></h3>
	</div>
</div>
