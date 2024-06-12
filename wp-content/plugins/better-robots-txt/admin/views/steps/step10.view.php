<div class="rt-segment">

    <h3><?php echo __( 'STEP 10 - PERSONALIZE YOUR ROBOTS.TXT:', 'better-robots-txt' ); ?></h3>
                
    <div class="rt-row">
    
        <div class="rt-column col-3">
            <span class="rt-label"><?php echo __( 'Be unique', 'better-robots-txt' ); ?></span>
        </div>
        
        <div class="rt-column col-9">
            <textarea name="personalize" rows="4" class="rt-area" id="personalize"><?php if ( $options::check('personalize') ) echo stripslashes( $options::get('personalize') ); ?></textarea>
            <div class="rt-alert rt-warning">
                <span class="closebtn">&times;</span> 
                <?php echo __( 'Create a unique signature like:', 'better-robots-txt' ); ?> <a href="https://store.nike.com/robots.txt" target="_blank">NIKE</a>, <a href="https://www.tripadvisor.com/robots.txt" target="_blank">TRIPADVISOR</a>, <a href="https://www.youtube.com/robots.txt" target="_blank">YOUTUBE</a>, <a href="https://www.yelp.com/robots.txt" target="_blank">YELP</a>
            </div>
        </div>
        
    </div>
    
    <div class="rt-row">

        <p style="margin-left: 240px; font-size: 13px; line-height: 80%; padding: 0; margin-bottom: 0"></p>
        
        <div class="rt-column col-3">
            <span class="rt-label"><?php 
            echo  __( 'Be part of our Movement against CoronaVirus', 'better-robots-txt' ) ;
            ?></span>
            <div class="rt-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="rt-tooltiptext"><?php echo  __( 'Disallow CoronaVirus (Covid-19) from exploring the world and infecting humanity.', 'better-robots-txt' ) ; ?></span>
            </div>  
        </div>
        
        <div class="rt-column col-9">

            <label class="rt-switch">
                <input type="checkbox" id="covid-19" name="covid-19" value="allow"
                <?php if ( $options::check('covid-19') ) { echo  'checked' ;  } ?> />
                <span class="rt-slider rt-round"></span>
            </label>
            &nbsp;
            <span>
                <?php echo sprintf( wp_kses( __( 'This feature will add a message of hope in your robots.txt for all mankind (like <a href="%s" target="_blank">this</a>)', 'better-robots-txt' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( ROBOTS_PLUGIN_DIR . '/admin/assets/imgs/corona.jpg' ) ); ?>
            </span>
        
        </div>
    
    </div>

</div>