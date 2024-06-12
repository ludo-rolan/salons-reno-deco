<div class="rt-segment">

    <h3><?php 
echo  __( 'STEP 5 - IMAGE CRAWLABILITY BY SEARCH ENGINES:', 'better-robots-txt' ) ;
?></h3>
                
    <div class="rt-row">
    
        <div class="rt-column col-3">
            <span class="rt-label"><?php 
echo  __( 'Allow/Disallow .Webp, .Png, .Jpg, .gif', 'better-robots-txt' ) ;
?></span>
        </div>
        
        <div class="rt-column col-9">
                
            <div class="rt-switch-radio dual-btns">

            <?php 
// free only
?>

                <input type="radio" id="image_crawlability-btn1" value="" disabled />
                <label for="image_crawlability-btn1"><?php 
echo  __( 'Allow', 'better-robots-txt' ) ;
?></label>

                <input type="radio" id="image_crawlability-btn2" value="" disabled />
                <label for="image_crawlability-btn2"><?php 
echo  __( 'Disallow', 'better-robots-txt' ) ;
?></label>

                <input type="radio" id="image_crawlability-btn3" value="" checked="checked" />
                <label for="image_crawlability-btn3"><?php 
echo  __( 'Disable', 'better-robots-txt' ) ;
?></label>

            <?php 
// end free only
?>

                <div class="rt-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="rt-tooltiptext"><?php 
echo  __( 'Allow/disallow your images (.Webp, .Png, .Jpg, ...) from being crawled/indexed by search engines', 'better-robots-txt' ) ;
?></span>
                </div>

            </div>

            <?php 
// free only
?>
            <div class="rt-alert rt-info">
                <span class="closebtn">&times;</span> 
                <?php 
echo  $get_pro . " " . __( 'Allow/Disallow .Webp, .Png, .Jpg, .gif feature', 'better-robots-txt' ) ;
?>
            </div>
            <?php 
?>
            
        </div>
    
    </div>
    
</div>