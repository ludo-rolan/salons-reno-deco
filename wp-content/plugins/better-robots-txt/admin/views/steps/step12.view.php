<div class="rt-segment">

    <h3><?php echo  __( 'STEP 12 - LOOKING FOR MORE WP PLUGINS (SEO)?:' );?></h3>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
        echo  __( 'Boost your Alt texts', "better-robots-txt" ) ;
        ?></span>
        </div>
        
        <div class="rt-column col-9">
            
        <label class="rt-switch rt-boost-alt-label">
            <input type="checkbox" id="boost-alt" name="boost-alt" value="allow" <?php if ( $options::check('boost-alt') ) { echo  'checked="checked"'; } ?> />
            <span class="rt-slider rt-round"></span>
        </label>

            &nbsp; <span><?php 
        echo  __( 'Boost your ranking with optimized Alt tags', "better-robots-txt" ) ;
        ?></span>
            
            <div class="rt-boost-alt" <?php if ( $options::check('boost-alt') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

                <div class="rt-alert rt-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">BIALTY Wordpress plugin</a> & auto-optimize all your alt texts for FREE', "better-robots-txt" ), array( 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ), 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ),
                    ) ), esc_url( "https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/" ), esc_url( "https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/" ) ); ?>
                </div>
            </div>
        </div>
        
    </div>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
        echo  __( 'Mobile-Friendly & responsive design', "better-robots-txt" ) ;
        ?></span>
        </div>
        
        <div class="rt-column col-9">
            
        <label class="rt-switch rt-mobi-label">
            <input type="checkbox" id="rt-mobilook" name="rt-mobilook" value="allow" <?php if ( $options::check('rt-mobilook') ) { echo  'checked="checked"'; } ?> />
            <span class="rt-slider rt-round"></span>
        </label>

            &nbsp; <span><?php 
        echo  __( 'Get dynamic mobile previews of your pages/posts/products + Facebook debugger', "better-robots-txt" ) ;
        ?></span>
            
            <div class="rt-mobi" <?php if ( $options::check('rt-mobilook') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

                <div class="rt-alert rt-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">Mobilook</a> and test your website on Dualscreen format (Galaxy fold)', "better-robots-txt" ), array( 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ), 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ),
                    ) ), esc_url( "https://wordpress.org/plugins/mobilook/" ), esc_url( "https://wordpress.org/plugins/mobilook/" ) ); ?>
                </div>
            </div>
        </div>
        
    </div>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
        echo  __( 'Boost your image title attribute', "better-robots-txt" ) ;
        ?></span>
        </div>

        <div class="rt-column col-9">

        <label class="rt-switch rt-bigta-label">
            <input type="checkbox" id="rt-bigta" name="rt-bigta" value="allow" <?php if ( $options::check('rt-bigta') ) { echo  'checked="checked"'; } ?> />
            <span class="rt-slider rt-round"></span>
        </label>

        &nbsp; <span><?php 
        echo  __( 'Optimize all your image title attributes for UX & search engines performance', "better-robots-txt" ) ;
        ?></span>

            <div class="rt-bigta" <?php if ( $options::check('rt-bigta') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

                <div class="rt-alert rt-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">BIGTA</a> Wordpress plugin & auto-optimize all your image title attributes for FREE', "better-robots-txt" ), array( 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ), 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ),
                    ) ), esc_url( "https://wordpress.org/plugins/bulk-image-title-attribute/" ), esc_url( "https://wordpress.org/plugins/bulk-image-title-attribute/" ) ); ?>
                </div>

            </div>

        </div>

    </div>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
        echo  __( 'Boost your SEO with META Keywords', "better-robots-txt" ) ;
        ?></span>
        </div>

        <div class="rt-column col-9">

        <label class="rt-switch rt-meta-label">
            <input type="checkbox" id="rt-meta" name="rt-meta" value="allow" <?php if ( $options::check('rt-meta') ) { echo  'checked="checked"'; } ?> />
            <span class="rt-slider rt-round"></span>
        </label>

        &nbsp; <span><?php 
        echo  __( 'Auto-populate custom SEO META Keywords everywhere on your website based on Post titles, Yoast/Rank Math Focus keywords & more.', "better-robots-txt" ) ;
        ?></span>

            <div class="rt-meta" <?php if ( $options::check('rt-meta') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

                <div class="rt-alert rt-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">META TAGS for SEO</a> Wordpress plugin & optimize your website for search engines', "better-robots-txt" ), array( 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ), 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ),
                    ) ), esc_url( "https://wordpress.org/plugins/meta-tags-for-seo/" ), esc_url( "https://wordpress.org/plugins/meta-tags-for-seo/" ) ); ?>
                </div>

            </div>

        </div>

    </div>

    <div class="rt-row">

        <div class="rt-column col-3">
            <span class="rt-label"><?php 
        echo  __( 'Looking for FREE unlimited content for SEO', "better-robots-txt" ) ;
        ?></span>
        </div>
        
        <div class="rt-column col-9">
            
        <label class="rt-switch rt-vidseo-label">
            <input type="checkbox" id="rt-vidseo" name="rt-vidseo" value="allow" <?php if ( $options::check('rt-vidseo') ) { echo  'checked="checked"'; } ?> />
            <span class="rt-slider rt-round"></span>
        </label>

        &nbsp; <span><?php 
        echo  __( 'Boost your website SEO with videos transcription.', "better-robots-txt" ) ;
        ?></span>
            
            <div class="rt-vidseo" <?php if ( $options::check('rt-vidseo') ) { echo 'style="display: inline;"'; } else { echo 'style="display: none;"';} ?>>

            <div class="rt-alert rt-success" style="margin-top: 10px;"><?php echo sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to learn more about <a href="%2s" target="_blank">VidSEO</a> Wordpress plugin & how to skyrocket your SEO', "better-robots-txt" ), array( 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ), 
                        'a' => array( 
                            'href' => array(), 
                            'target' => array(), 
                        ),
                    ) ), esc_url( "https://wordpress.org/plugins/vidseo/" ), esc_url( "https://wordpress.org/plugins/vidseo/" ) ); ?>
                </div>
            </div>
        </div>
        
    </div>

</div>