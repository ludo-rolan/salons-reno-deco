<div class="rt-segment">

    <h3><?php 
echo  __( 'STEP 4 - HELP SEARCH ENGINES BOTS EXPLORE, CRAWL & INDEX ALL YOUR WEBPAGES:', 'better-robots-txt' ) ;
?></h3>
            
    <div class="rt-row">
    
        <div class="rt-column col-3">
            <span class="rt-label"><?php 
echo  __( 'Boost your ranking with XML sitemap', 'better-robots-txt' ) ;
?></span>
            &nbsp;
            <div class="rt-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="rt-tooltiptext"><?php 
echo  __( 'Add your sitemap in the robots.txt file to boost your ranking', 'better-robots-txt' ) ;
?></span>
            </div>
        </div>
        
        <div class="rt-column col-9">
            
            <?php 
// free only
?>
            
                <input type="text" name="" id="sitemap_file" class="rt-field" disabled>
                
                <?php 
echo  $sitemap_notification ;
?>
    
            <?php 
// end free only
?>
            
        </div>
        
    </div>
    
    <div class="rt-row">
    
        <div class="rt-column col-3">
            <span class="rt-label"><?php 
echo  __( 'Custom rules (for experts)', 'better-robots-txt' ) ;
?></span>
        </div>
        
        <div class="rt-column col-9">
    
            <textarea name="user_agents" rows="4" class="rt-area" id="user_agents"><?php 
if ( $options::check( 'user_agents' ) ) {
    echo  stripslashes( $options::get( 'user_agents' ) ) ;
}
?></textarea>
    
            <div class="rt-alert rt-warning">
                <span class="closebtn">&times;</span>
                <?php 
echo  __( 'Add more custom rules if you need them, otherwise, leave it with default rules.', 'better-robots-txt' ) ;
?>
            </div>
        </div>
        
    </div>
        
    <div class="rt-row">
    
        <div class="rt-column col-3">
            <span class="rt-label"><?php 
echo  __( 'Crawl-delay', 'better-robots-txt' ) ;
?></span>
        </div>
        
        <div class="rt-column col-9">
            <input type="number" name="crawl_delay" id="crawl_delay" class="rt-field" value="<?php 
if ( $options::check( 'crawl_delay' ) ) {
    echo  stripslashes( $options::get( 'crawl_delay' ) ) ;
}
?>">
            <div class="rt-alert rt-warning">
                <span class="closebtn">&times;</span> 
                <?php 
echo  __( 'The crawl-rate defines the time between requests bots make to your website in seconds. e.g: 10', 'better-robots-txt' ) ;
?>
            </div>
        </div>
        
    </div>

</div>
