<div class="rt-segment">

    <h2><?php 
echo  esc_html__( 'STEP 3 - LOADING PERFORMANCE FOR WOOCOMMERCE:', "better-robots-txt" ) ;
?></h2>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
echo  __( 'Optimize store\'s crawlability', 'better-robots-txt' ) ;
?></span>
        </div>
            
        <div class="rt-column col-9">
            
            <?php 
// free only
?>
            
                <div class="rt-switch-radio dual-btns">
                    <input type="radio" id="woocom_links-btn1" disabled />
                    <label for="woocom_links-btn1"><?php 
echo  __( 'Allow', 'better-robots-txt' ) ;
?></label>

                    <input type="radio" id="woocom_links-btn2" checked="checked" />
                    <label for="woocom_links-btn2"><?php 
echo  __( 'Disable', 'better-robots-txt' ) ;
?></label>

                    <div class="rt-tooltip">
                        <span class="dashicons dashicons-editor-help"></span>
                        <span class="rt-tooltiptext"><?php 
echo  __( 'Hide your backlinks from your competitors. This is a pro version feature', 'better-robots-txt' ) ;
?></span>
                    </div>
                    <div class="rt-tooltip-after"><img src="<?php 
echo  ROBOTS_PLUGIN_DIR ;
?>/admin/assets/imgs/star-new3.png" alt="" /></div>
                </div>
                <br />
                <div class="rt-alert rt-info">
                    <span class="closebtn">&times;</span> 
                    <?php 
echo  $get_pro . " " . __( 'Loading Performance for Woocommerce', 'better-robots-txt' ) ;
?>
                </div>
            
            <?php 
// end free only
?>
            
        </div>

    </div>

</div>