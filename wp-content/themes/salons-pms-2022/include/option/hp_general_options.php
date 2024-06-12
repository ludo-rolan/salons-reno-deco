<?php
class Hp_General_Option
{

    public static  function register()
    {
        add_action('wp_ajax_data_fetch', [self::class, 'data_fetch']);
        add_action('hp_option_post_link', [self::class, 'hp_option_post_link'], 10);
        add_action('hp_option_input_container', [self::class, 'hp_option_input_container']);
    }
    static function hp_option_input_container($type) {
        ?>
        <input type="hidden" name="type" id="type" value="<?php echo $type ?>"></input>
        <input type="text" name="keyword" id="keyword" onkeyup="fetch()"></input>
        <div id="datafetch">Résultats de recherche apparaîtront ici ...</div>
        <?php 
    }

    static function hp_option_post_link ($id) {
        if ($id != '') {
            $post = get_post($id);
            
                echo "<a href=" . admin_url('post.php?post=' . $post->ID) . '&action=edit' . ">"
                    . ($post->ID) . '-' . $post->post_title;
                    
        }
        
    }

    function data_fetch()
    {
        $padding="text-align: center; padding:25px 10px 5px 0;";
        $the_query = new WP_Query(
            array(
                'posts_per_page' => -1,
                's' => esc_attr($_POST['keyword']),
                'post_type' => esc_attr($_POST['type'])
            )
        );


        if ($the_query->have_posts()) :
?>
<style>
     .copy_paste[tooltip]:focus:before {
        content: attr(tooltip);
        display: block;
        position: relative;
        margin-top: -30px;
        color: #135e96;
    }
</style>
<table class='wp-list-table widefat striped fixed' style="width:50%;">
<thead>
      <tr>
        <?php 
            // Les titres sont définis dans chaque thème.
            do_action('hp_option_search_table_header',$padding);
        ?>
      </tr>
    </thead>
    <tbody>
        <?php
            while ($the_query->have_posts()) : $the_query->the_post();

                $myquery = esc_attr($_POST['keyword']);
                $a = $myquery;
                $search = get_the_title();
                if (stripos("/{$search}/", $a) !== false) { ?>
                  <tr class="">
                    <td style="<?php echo $padding ?>"> <?php the_ID() ?> </td> 
                    <td style="<?php echo $padding ?>"><a tooltip="ID copié"  class="copy_paste" href="javascript:copy_paste_to_clipboard(<?php the_ID() ?>)" ><?php the_title(); ?></a></td>
                    <td style="<?php echo $padding ?>"> <button tooltip="ID copié" type="button" class =" copy_paste button-primary" onclick="copy_paste_to_clipboard(<?php the_ID() ?>)"> Copier</button></td>
                    <td style="<?php echo $padding ?>"> <a class ="button-primary" target="_blank" href="<?php echo the_permalink(); ?>"> Voir</a></td> 
                  </tr>
<?php
                }
            endwhile;
            ?>
            </tbody>
            </table>
    <?php        
            wp_reset_postdata();
        endif;

        die();
    }

}

Hp_General_Option::register();
