<div class="rt-segment">

    <h3><?php 
echo  __( 'STEP 6 - AVOID CRAWLER TRAPS CAUSING CRAWL BUDGET ISSUES:', 'better-robots-txt' ) ;
?></h3>
                
    <div class="rt-row">
    
    <div class="rt-column col-3">
        <span class="rt-label"><?php 
echo  __( 'Stop crawling useless & toxic links', 'better-robots-txt' ) ;
?></span>
    </div>
    
    <div class="rt-column col-9">
        
        <?php 
// free only
?>
    
        <div class="rt-switch-radio dual-btns">

            <input type="radio" id="crawl_budget-btn1" value="" disabled />
            <label for="crawl_budget-btn1"><?php 
echo  __( 'Allow', 'better-robots-txt' ) ;
?></label>

            <input type="radio" id="crawl_budget-btn2" value="" checked="checked" />
            <label for="crawl_budget-btn2"><?php 
echo  __( 'Disable', 'better-robots-txt' ) ;
?></label>

            <div class="rt-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="rt-tooltiptext"><?php 
echo  __( '“Crawler traps” are a structural issue within a website that causes crawlers to find a virtually infinite number of irrelevant URLs. In theory, crawlers could get stuck in one part of a website and never finish crawling these irrelevant URLs. Crawler traps hurt crawl budget and cause duplicate content. More info : https://www.contentkingapp.com/academy/crawler-traps', 'better-robots-txt' ) ;
?></span>
            </div>

            <div class="rt-tooltip-after">
                <img src="<?php 
echo  ROBOTS_PLUGIN_DIR ;
?>/admin/assets/imgs/star-new4.png" alt="" />
            </div>

        </div>

        <div class="rt-alert rt-info">
            <span class="closebtn">&times;</span> 
            <?php 
echo  $get_pro . " " . __( 'Avoid crawler traps', 'better-robots-txt' ) ;
?>
        </div>
            
    
        <?php 
// end free only
?>
    
        </div>
    
    </div>
    
</div>