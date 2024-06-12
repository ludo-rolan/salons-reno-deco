$(document).ready(function(){
	setTimeout(function(){ 
		send_GA( "event_form","sidebar",self.location.href);
	}, 3000); 
	$('.offer_checkbox').on('click', function(){
		$(this).parent(".offers_item").toggleClass('selected');
	});

	var birthday = $( '#birthday-date_nl, #birthday-date' );
	if ( birthday.length > 0 ) {
		birthday.inputmask( 'dd/mm/yyyy', { placeholder: '__/__/____' } );
	}

	var codepost = $( '#codepostal_nl, #codepostal' );
	if ( codepost.length > 0 ) {
		codepost.inputmask( '99999', { placeholder: '_____' } );
	}

	if( $("#signupForm_nl").length > 0 ){

		var validation_msgs = {
				NAME: "Veuillez entrer votre nom",
				FIRSTNAME: "Veuillez entrer votre prenom",
				CODE_POSTAL: {
					required: "Veuillez entrer le code postal",
					number: "Veuillez entrer que des chiffres",
					minlength: "Veuillez entrer au moins 5 chiffres",
					maxlength: "Veuillez entrer seulement 5 chiffres",
				},
				MAIL: "Veuillez entrer un email valide",
				BDATE: "Veuillez entrer une date de naissance valide",
				COUNTRY: "Veuillez entrer un pays valide",
				SECTEUR: "Veuillez sélectionner un secteur",
				PROFESSION: "Veuillez sélectionner une profession",
				CATEGORY_CHECKBOX_TEST: "Veuillez cocher au moins une des cases pour recevoir l\'actualité exclusive du Mondial de l\'Auto",
		};
		var lang = $('html').attr('lang');
		if( lang != 'undefined' && lang.indexOf("en") !== -1 ){
			validation_msgs = {
				NAME: "Please enter your last name",
				FIRSTNAME: "Please enter your first name",
				CODE_POSTAL: {
					required: "Please enter a postal code",
					number: "Only number are accepted",
					minlength: "Please enter minimum 5 characters",
					maxlength: "Please only enter 5 characters",
				},
				MAIL: "Please enter a valid Email",
				BDATE: "Please enter a valid birth date",
				COUNTRY: "Please enter your country",
				CATEGORY_CHECKBOX_TEST: "Please check at least one of the boxes to receive exclusive news from the Mondial de l'Auto",
			};
		}else if( lang != 'undefined' && lang.indexOf("ar") !== -1 ){
			validation_msgs = {
				NAME: "المرجو ادخال الاسم الأخير",
				FIRSTNAME: "المرجو ادخال الاسم الاول",
				MAIL: "المرجو ادخال البريد الإلكتروني",
				BDATE: "المرجو ادخال تاريخ الميلاد",
				COUNTRY: "المرجو ادخال البلد",
			};
		}

		$("#signupForm_nl").validate({
			rules: {
				NAME: "required",
				FIRSTNAME: "required",
				CODE_POSTAL: {
					required: true,
					number: true,
					minlength: 5,
					maxlength: 5,
				},
				MAIL: {
					required: true,
					email: true
				},
				BDATE: {
					anyDate: true
				},
				COUNTRY: {
					required: true,
					maxlength: 10,
				},
				SECTEUR: "required",
				PROFESSION: "required",
				CATEGORY_CHECKBOX_TEST: {
					valid_optins: true
				},
			},
			messages: validation_msgs
		});


		jQuery.validator.addMethod("anyDate",
			function(value, element) {
				return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{2}$/);
			},
			"Veuillez entrer une date de naissance"
		);
		
		jQuery.validator.addMethod("valid_optins",function () {
				console.log("valid_optins");
				return $(".checkbox.offers input[type=checkbox]:checked").length > 0;
			}, 
			"Veuillez cocher au moins une des cases pour recevoir l\'actualité exclusive du Mondial de l\'Auto"
		);
		
	}



	$('#signupForm_nl').on('submit', function (e) {
		e.preventDefault();
		if($("#signupForm_nl").valid()){
			var email = $( '#email_nl, #email' ).val();
			var name = $( '#nom_nl, #nom' ).val();
			var prenom = $( '#prenom_nl, #prenom' ).val();
			var civilite = $( 'input[name=civilite_nl]:checked, input[name=civilite]:checked', '#signupForm_nl' ).val();
			var date_de_naissance = $( '#birthday-date_nl, #birthday-date' ).val();
			var optins = [];
			var optins_categories = [];
			var checked = $(".offers_item input[name='PART_CHECKBOX']").is(':checked') ;
			var country = $("#country_nl, #country").val();
			var secteur = $("#secteur_nl, #secteur").val();
			var profession = $("#profession_nl, #profession").val();

			$('.offers input:checkbox:checked').each(function () {
				optins.push($(this).val());
			});
			$('.offers input.category-optin:checkbox:checked').each(function () {
				optins_categories.push($(this).val());
			});
			var code_postal = $('#codepostal_nl, #codepostal').val();
			var lang = $('html').attr('lang');
			if( lang != 'undefined' ){
				if( lang.indexOf('ar') !== -1 ){
					lang = 'ar';
				}else if( lang.indexOf('en') !== -1 ){
					lang = 'en';
				}
			}

			var fields_data = {
				lang				: lang,
				email				: email,
				name 				: name,
				prenom				: prenom,
				civilite			: civilite,
				date_de_naissance	: date_de_naissance,
				country 			: country,
				code_postal 		: code_postal,
				secteur             : secteur,
				profession          : profession,
				checked				: checked,
				optins 				: optins,
				optins_categories 	: optins_categories,
			};

			// $.ajax({
			// 	url : '/',
			// 	type : 'POST',
			// 	dataType: 'json',
			// 	data : {
			// 		action				: 'update_user_chetaah_ajax',
			// 		src_nl				: 'NL',
			// 		fields : fields_data
			// 	},
			// 	success : success_request,
			// });

			$.ajax({
				url : '/',
				type : 'POST',
				dataType: 'json',
				data : {
					action				: 'update_user_sendinblue_ajax',
					src_nl				: 'NL',
					fields : fields_data
				},
				success : success_request,
			});
			
			function success_request( response ) {
				$('#nl_body').remove();
				setTimeout(function(){ 
					send_GA( "event_form","landing_page",self.location.href);
				}, 3000); 
				if(response === 'NEW_USER' || response === 'NOT_EXIST'){
					var $h3 = $('#confirmation-new h3');
					var htmlString = $( $h3 ).html();
					$( $h3 ).html( htmlString.replace('MAIL_NL', email) );
					$('#confirmation-old').remove();
					$('#confirmation-new').css({
						'display': 'block'
					});
					$('html,body').animate({scrollTop: $("#content").offset().top},'fast');
				}else if(response === 'UPDATE_USER' || response === 'EXIST'){
					var $h3 = $('#confirmation-old h3');
					var htmlString = $( $h3 ).html();
					$( $h3 ).html( htmlString.replace('MAIL_NL', email) );
					$('#confirmation-new').remove();
					$('#confirmation-old').css({
						'display': 'block'
					});
					$('html,body').animate({scrollTop: $("#content").offset().top},'fast');
				}
				return false;
			}
		}
	});
});




