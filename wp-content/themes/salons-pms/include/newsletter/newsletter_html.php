<div class="nl-body jlm">
	<div id="nl_body">
		<h2 class="text-center page-title">Inscription newsletter</h2>
		<h3 class="text-center page-sub-title">Inscrivez-vous pour recevoir le meilleur de Mondial Auto Paris</h3>
		<form action="" method="post" class="form-horizontal" id="signupForm_nl">
			<div class="offers">
				<div class="offers_item">
					<input type="checkbox" value="pms_optin_edito" class="offer_checkbox" id="choice-1" class="offer_checkbox" name="OPTIN_CHECKBOX" />
					<label for="choice-1"></label>
					<span class="check-box"></span>
					<strong>La newsletter Mondial Auto Paris :</strong> retrouvez nos dernières actualités !
					<ul class="list-unstyled list-inline pull-right offers_const">
						<li class="offers_frequancy">2j/7</li>
						<li class="offers_time">9h</li>
					</ul>
				</div>

				<div class="offers_item">
					<input type="checkbox" value="pms_optin_part" id="choice-3" class="offer_checkbox" class="offer_checkbox" name="PART_CHECKBOX" />
					<label for="choice-3"></label>
					<span class="check-box"></span>
					<strong>Recevoir les bons plans des partenaires de Mondial Auto Paris <span class="double_asterisk">**</span></strong>
				</div>

			</div>
			<div class="row sign-form">
				<h3 class="text-center page-title">Je m'inscris gratuitement</h3>
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="row">
						<div class="col-xs-12">
							<label for="exampleInputEmail1" class="block">Civilité*</label>

							<input type="radio" value="1" id="civilite_me" name="civilite_nl" />
							<label class="radio-inline" for="civilite_me">
								<span></span>Mr
							</label>

							<input type="radio" value="2" id="civilite_mme" value="mme" name="civilite_nl" checked>
							<label class="radio-inline" for="civilite_mme">
								<span></span>Mme
							</label>

						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<label for="prenom">Prénom*</label>
								<input type="text" class="form-control" placeholder="Prénom" id="prenom_nl" name="FIRSTNAME"/>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<label for="nom">Nom*</label>
								<input type="text" class="form-control" placeholder="Nom" id="nom_nl" name="NAME"/>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label for="email">Adresse mail*</label>
								<input type="email" required name="MAIL" checked="checked" <?php echo (isset($_GET["email_newsletter"]) && $_GET["email_newsletter"]) ? "value=".$_GET["email_newsletter"] : ''; ?> class="form-control" placeholder="Adresse mail" id="email_nl"/>
							</div>
						</div>
						<div class="col-xs-12">
							<button type="submit" class="btn btn-default">Je m'inscris</button>
							<p class="help-block">* tous les champs sont obligatoires</p>
						</div>
					</div>
				</div>
			</div>
			<!-- <label tabindex="0" class="radio" for="193_1">Oui</label> -->
		</form>
		<div class="nl-feature">
			<h2 class="text-center page-title">Nos engagements</h2>
			<div class="row">
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="book-icon"></span>
					Vous offrir gratuitement nos contenus exclusifs
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="folder-icon"></span>
					Vous permettre d'accéder aux dossiers de la rédac'
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="msg-icon"></span>
					Vous accompagner tout au long de votre parcours
				</div>
				<div class="col-xs-6 col-md-3 nl-feature-item">
					<span class="lock-icon"></span>
					Respecter vos données personnelles
				</div>
			</div>
		</div>
	</div>
	<div id="confirmation-old" style="display: none">
		<h2 class="text-center page-title">Merci !</h2>
		<h3 class="text-center page-sub-title">Nous avons bien pris en compte votre demande pour l'adresse MAIL_NL<br /><br>À très vite sur Mondial Auto Paris !</h3>
	</div>

	<div id="confirmation-new" style="display: none">
		<h2 class="text-center page-title">Merci !</h2>
		<h3 class="text-center page-sub-title">Nous avons bien pris en compte votre demande pour l'adresse MAIL_NL<br>
			Vous venez de recevoir un <strong>mail de confirmation d'inscription</strong> (n'oubliez pas de regarder dans vos courriers indésirables).<br>
			<br>
		À très vite sur Mondial Auto Paris !</h3>
	</div>
</div>
