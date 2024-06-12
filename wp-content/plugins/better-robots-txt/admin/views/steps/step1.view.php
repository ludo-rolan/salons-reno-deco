<div class="rt-segment">

    <h2><?php 
echo  esc_html__( 'STEP 1 - IDENTIFY WHICH SEARCH ENGINES SHOULD CRAWL (OR NOT) YOUR WEBSITE:', "better-robots-txt" ) ;
?></h2>

    <?php 
foreach ( $agents as $key => $bot ) {
    ?>
    <div class="rt-row">

        <div class="rt-column col-3">
            <?php 
    echo  "<span class='rt-label'>" . $bot['name'] . "</span>" ;
    ?>
        </div>

        <div class="rt-column col-9">
            
            <div class="rt-switch-radio">

                <input type="radio" id="<?php 
    echo  $bot['slug'] ;
    ?>-btn1" name="<?php 
    echo  $bot['slug'] ;
    ?>" value="allow" <?php 
    if ( $options::check( $bot['slug'] ) ) {
        checked( 'allow' == $options::get( $bot['slug'] ) );
    }
    ?> >

                <label for="<?php 
    echo  $bot['slug'] ;
    ?>-btn1"><?php 
    echo  __( 'Allow', 'better-robots-txt' ) ;
    ?></label>
                
                <input type="radio" id="<?php 
    echo  $bot['slug'] ;
    ?>-btn2" name="<?php 
    echo  $bot['slug'] ;
    ?>" value="disallow" <?php 
    if ( $options::check( $bot['slug'] ) ) {
        checked( 'disallow' == $options::get( $bot['slug'] ) );
    }
    ?> />

                <label for="<?php 
    echo  $bot['slug'] ;
    ?>-btn2"><?php 
    echo  __( 'Disallow', 'better-robots-txt' ) ;
    ?></label>
                
                <input type="radio" id="<?php 
    echo  $bot['slug'] ;
    ?>-btn3" name="<?php 
    echo  $bot['slug'] ;
    ?>" value="disable" <?php 
    if ( empty($options::get( $bot['slug'] )) || $options::get( $bot['slug'] ) == "disable" ) {
        echo  'checked="checked"' ;
    }
    ?> />

                <label for="<?php 
    echo  $bot['slug'] ;
    ?>-btn3"><?php 
    echo  __( 'Disable', 'better-robots-txt' ) ;
    ?></label>

                <div class="rt-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="rt-tooltiptext"><?php 
    printf(
        __( 'Allows %1$s %2$s to index => %3$s', 'better-robots-txt' ),
        $bot['name'],
        $bot['define'],
        $bot['path']
    );
    ?></span>
                </div>

            </div>
            
        </div>
        
    </div>
    <?php 
}
?>

    <div class="rt-row">

    <div class="rt-column col-3">
        <span class="rt-label"><?php 
echo  __( 'Baidu/Sogou/Soso/Youdao - Chinese search engines', 'better-robots-txt' ) ;
?></span>
    </div>

    <div class="rt-column col-9">

        <?php 
// free only
?>
            
            <div class="rt-switch-radio dual-btns">
                <input type="radio" id="chinese_bot-btn1" name="chinese_bot" value="" disabled />
                <label for="chinese_bot-btn1"><?php 
echo  __( 'Allow', 'better-robots-txt' ) ;
?></label>

				<input type="radio" id="chinese_bot-btn2" name="chinese_bot" value="" disabled />
                <label for="chinese_bot-btn2"><?php 
echo  __( 'Disallow', 'better-robots-txt' ) ;
?></label>

                <input type="radio" id="chinese_bot-btn3" name="chinese_bot" value="" checked="checked" />
                <label for="chinese_bot-btn3"><?php 
echo  __( 'Disable', 'better-robots-txt' ) ;
?></label>

                <div class="rt-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="rt-tooltiptext"><?php 
echo  __( 'Baidu, Soso, Youdao, Sogou search engines', 'better-robots-txt' ) ;
?></span>
                </div>
            </div>
			<br />
			<div class="rt-alert rt-info">
				<span class="closebtn">&times;</span> 
				<?php 
echo  $get_pro . " " . __( 'Popular Chinese search engines feature', 'better-robots-txt' ) ;
?>
			</div>
        
        <?php 
// end free only
?>

        </div>

    </div>

</div>