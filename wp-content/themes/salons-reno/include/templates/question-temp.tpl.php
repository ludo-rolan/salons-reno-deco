<?php 
	$question_position =$question->get_position();
	?>
	<div class="clearfix"></div>
	<div class="main question" itemscope itemtype="http://schema.org/Question">
		<?php require $this->locate_template('image-test.tpl.php') ;  ?>
		

		<div class="row">
			<div class="col-xs-12 test_progress pull-left">
				<div class="col-xs-12 progress_bar tex-center">
				<?php
					$total =100;
					$wd = $total/$questions_count ;
					for ($i=1; $i < $questions_count+1; $i++) { 
				?>
		  				<span class="steps text-center <?php if($i == $question_position) echo 'current'; ?>">
		  					<span><span><?php echo $i;?></span></span>
		  				</span>
						
					<?php
					}
				  	?>
				</div>

			</div>
		</div>
		<form action="<?php echo get_permalink($quizz->get_id());?>" method="post" id="answer_quizz_form" name="answer_quizz_form">
			<input type="hidden" name="id_question" value="<?php echo $question->get_id() ?>" />
			<div class="col-md-6 content pull-left">
				<div class="title-question text-uppercase">Question 
					<span class="number_label"><?php echo $question_position ; ?></span>
				</div>
				<div class="article-intro excerpt" itemtype="http://schema.org/Question">
		        	<h2><?php echo $question->get_text()?></h2>
		    	</div>
				<div class="responses col-md-12 pull-left" itemprop="suggestedAnswer" itemtype="http://schema.org/Answer">
				<?php $question_responses =$question->get_responses() ;
				foreach ($question_responses as $response) {
					?>
				
						<label class="radio text-uppercase">
							<input type="radio" required name="id_response" id="response_<?php echo $response->get_id();?>" value="<?php echo $response->get_id()?>"  <?php if ( isset($answer) && $answer->get_response_id() == $response->get_id() ) echo 'checked="checked"'?> />
								<span class="radio_btn"></span>
								<span class="q_txt"><?php echo $response->get_text();?></span>
						</label>
					<?php } ?>
				</div>
				<div class="col-md-12 pull-left btn-result" >

					
					  <button class="btn-arrow-link next pull-right" type="submit">
					  <?php if (!$question->get_next_question()){?>
					  	<?php _e('Your results', TEST_PHALCON_TERMS); ?>
					  <?php }else{?>
					  	<?php _e('Next question', TEST_PHALCON_TERMS); ?>
					  <?php }?>

					  </button>
					<!--button type="submit" value="" class="btn-default btn-arrow-link-prev">
						{if !$question->get_next_question()}
							{t}<span class="hidden-tablet">Voir les </span>résultats{/t}
						{else}
							{t}<span class="hidden-tablet">Question </span>suivante{/t}
						{/if}
					</button-->
					  <?php if ( $question_previou = $question->get_previous_question()){
					  	$url_previous_question = get_permalink($quizz->get_id()) ;
					  	$url_previous_question = add_query_arg(array('id_question'=>$question_previou->get_id(), 'show' => 'back'), $url_previous_question);
					  	
					  ?>
						<a href="<?php echo $url_previous_question ;?>" class="btn-arrow-link previous pull-left"><?php _e('Previous question', TEST_PHALCON_TERMS); ?></a>
					  <?php }?>
					

				</div>
			</div>
		</form>
	</div>
				
			<?php
			