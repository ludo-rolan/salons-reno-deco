<?php

/**
 * Abstraction for the creation of post_metas
 * with many implementations for metaboxes in WordPress
 * such as : TextInputs, WPEditor ...
 * @class MetaBox_Factory
 */

class MetaBox_Factory
{
    /**
     * supported post_types for the meta collection
     * @var array post_types
     */
    protected $post_types = ['post'];

    /**
     * Metabox Definition
     * @var array meta_boxes
     */
    protected $meta_box = array();

    /**
     * metabox to create defining their types
     * @var array $meta_box_fields
     */
    protected $meta_box_fields = array();
    /**
     * condition to define if postmeta are created in a single way in the database
     * @var boolean $is_single_meta
     */
    protected $is_single_meta = false;
    /**
     * This is a Test Block tht will be deleted after Preparing the documentation
     * TODO: Add Documentation 
        array(
            "post_types" => array("post"), // supported CPTs
            "is_single_meta" => true, // if true, the postmeta will be created in a single way in the database
            "meta_box" => array(
                "id" => "test_meta", // meta ID to get using Options
                "title" => "Test Meta", // Meta Title
                "position" => "normal", // 'normal' or 'side'
                "priority" => "high", // placement on admin page
            ),
            "fields" => array(
                array(
                    "label" => "Test Meta Text",
                    "suffix_id" => "_text", // meta_box id + suffix_id
                    "sanitize" => true, //store without html tags
                    "type" => Text_MetaBox_Type::class,
                ),
                array(
                    "label" => "Test Meta WPEditor",
                    "suffix_id" => "_description",
                    "type" => WPEditor_MetaBox_Type::class
                ),
            )
        )
     */
    public function __construct($args)
    {
        $this->post_types = $args["post_types"];
        $this->meta_box = $args["meta_box"];
        $this->meta_box_fields = $args["fields"];
        $this->is_single_meta = $args["is_single_meta"]??false;

        add_action('admin_init', array(&$this, 'create_meta_box'));
        add_action('save_post', array(&$this, 'save_meta_box'));
    }

    function create_meta_box()
    {
        add_meta_box(
            $this->meta_box['id'], // $id
            __($this->meta_box['title'],  REWORLDMEDIA_TERMS), // $title
            array(&$this, 'render_meta_boxes'), // $callback
            $this->post_types, // $page /* stick with $post_type for now */
            $this->meta_box['position'], // $context /* 'normal' = main column. 'side' = sidebar */
            $this->meta_box['priority'] // $priority /* placement on admin page */
        );
    }

    function render_meta_boxes()
    {
        global $post;
        $metas=[];
        if ($this->is_single_meta) {
            foreach ($this->meta_box_fields as $field) {
                $meta_field_id = $this->meta_box['id'] . $field['suffix_id'];
                $metas[$meta_field_id]=get_post_meta($post->ID, $meta_field_id, true);
            }    
        }
        else {    
            $metas = get_post_meta($post->ID, $this->meta_box['id'], true);
        }
        foreach ($this->meta_box_fields as $field) {
            $meta_field_id = $this->meta_box['id'] . $field['suffix_id'];
            $value = "";
            if (!empty($metas)) {
                $value = $metas[$meta_field_id] ?? "";
            }
            $args=$field["args"]??[];
            $field_type = new $field['type'](
                $field['label'],
                $value,
                $meta_field_id,
                $args
            );
            $field_type->render_metabox_html();
        }
    }
    function save_meta_box($post_id)
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ($parent_id = wp_is_post_revision($post_id)) {
            $post_id = $parent_id;
        }
        if (isset($_POST['post_type']) && in_array($_POST['post_type'], $this->post_types)) {
            $meta_fields = array();     
            foreach ($this->meta_box_fields as $field) {
                $meta_field_id = $this->meta_box['id'] . $field['suffix_id'];
                $meta_val = "";
                if (array_key_exists($meta_field_id, $_POST)) {
                    if ($field["sanitize"]) {
                        $meta_val = sanitize_text_field($_POST[$meta_field_id]);
                    } else {
                        $meta_val = $_POST[$meta_field_id];
                    }
                } else{
                    if ($field["type"] == Checkbox_MetaBox_Type::class) {
                        $meta_val='false';
                    }
                }
                if($this->is_single_meta){
                    update_post_meta(
                        $post_id,
                        $meta_field_id,
                        $meta_val
                    );
                }
                else{
                    $meta_fields[$meta_field_id] = $meta_val;
                }
            }
            if(!$this->is_single_meta){
                update_post_meta(
                    $post_id,
                    $this->meta_box['id'],
                    $meta_fields
                );
            }
        
        }
    }
}

/**
 * Abstract Input Type Class
 * Extend if you want to include More Input types
 */
abstract class MetaBox_Type
{
    protected $box_value;
    protected $box_label;
    protected $meta_id;
    protected $args;
    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        $this->box_label = $box_label;
        $this->box_value = $box_value;
        $this->meta_id = $meta_id;
        $this->args = $args;
        // setting up the default value if provided
        if(!empty($this->args) && !empty($this->args['default']) && empty($box_value)) {
            $this->box_value = $this->args['default'];
        }
    }
    abstract public function render_metabox_html();
}


class Text_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }

    public function render_metabox_html()
    {
?>
        <p>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <input type="text" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" value="<?php echo $this->box_value; ?>" style="width:100%" />
        </p>
    <?php
    }
}

class WPEditor_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }
    public function render_metabox_html()
    {
    ?>
        <p>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <?php
            /* Add WP Editor as replacement of textarea */
            //TODO: Add more configurations
            wp_editor($this->box_value, $this->meta_id, array(
                'wpautop' => true,
                'media_buttons' => false,
                'textarea_name' => $this->meta_id,
                'textarea_rows' => 10,
                'teeny' => true
            ));
            ?>
        </p>
    <?php
    }
}

class ColorWheel_MetaBox_Type extends MetaBox_Type
{
    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id);
    }
    public function render_metabox_html()
    {
        global $site_config;
        if (isset($site_config["theme_colors"])){
            $colors =  json_encode($site_config["theme_colors"]);
        }
        
    ?>
        <p>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%">
                <?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <input data-jscolor="{}" id="<?php echo $this->meta_id; ?>" name="<?php echo $this->meta_id; ?>" value="<?php echo $this->box_value; ?>">
        </p>
        <script>
            jscolor.presets.default = {
                width: 141,
                position: 'left',
                previewPosition: 'bottom',
                previewSize: 40,
                preset: 'dark large',
                palette: <?php echo  $colors; ?>,
                paletteCols: 5,
                paletteHeight: 30
            };
        </script>
    <?php
    }
}
class Checkbox_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }

    public function render_metabox_html()
    {
    ?>
        <p>

            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <input type="checkbox" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" value="true" <?php echo (($this->box_value == 'true') ? "checked" : ""); ?> />

        </p>
    <?php
    }
}

class Single_image_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }

    public function render_metabox_html()
    {

        $image_url = '';
        if ($this->box_value !== '') {
            $image_url = wp_get_attachment_url($this->box_value);
        }

    ?>
        <div>

            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <div class="mx-image-uploader">
                <!-- button d'ajout d'image-->
                <button class="mx_upload_image button button-primary" <?php echo $image_url !== '' ? 'style="display: none;"' : ''; ?>><?php _e("Choisir une Image", REWORLDMEDIA_TERMS); ?></button>

                <!-- remove image -->
                <a href="#" class="mx_upload_image_remove button" style="color: #b32d2e; font-weight: 600; margin: 20px 0px;  <?php echo $image_url == '' ? 'display: none;' : ''; ?>"><?php _e("Supprimer L'image", REWORLDMEDIA_TERMS); ?></a>

                <!-- save an id of image -->

                <input type="hidden" class="mx_upload_image_save" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" value="<?php echo $this->box_value; ?>" />

                <!-- show an image -->
                <div>
                    <img src="<?php echo $image_url !== '' ? $image_url : ''; ?>" alt="" style="width: 300px; border: 1px solid; <?php echo $image_url == '' ? 'display:none;' : ''; ?>" class="mx_upload_image_show" />
                </div>


            </div>

        </div>
    <?php
    }
}

class Gallery_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }

    public function render_metabox_html()
    {
    ?>
        <div id="gallery">
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <div class="gallery-screenshot clearfix">
                <?php

                $ids = explode(',', $this->box_value);
                foreach ($ids as $attachment_id) {
                    $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    if(isset($img[0])){
                    echo '<div class="screen-thumb" style="display: inline-block; border: 1px solid #ccc; margin: 0 10px;"><img src="' . esc_url($img[0]) . '" /></div>';
                    }    
                }

                ?>
            </div>

            <input id="edit-gallery" class="button upload_gallery_button" type="button" value="<?php esc_html_e('Ajout/Modification Gallery', REWORLDMEDIA_TERMS) ?>" />
            <input id="clear-gallery" class="button upload_gallery_button" type="button" value="<?php esc_html_e('Vider', REWORLDMEDIA_TERMS) ?>" />
            <input type="hidden" name="<?php echo esc_attr($this->meta_id); ?>" id="<?php echo esc_attr($this->meta_id); ?>" class="gallery_values" value="<?php echo esc_attr($this->box_value); ?>">
        </div>
<?php
    }
}
class RadioButtons_MetaBox_Type extends MetaBox_Type
{
    private $inline_display;
    public function __construct($box_label, $box_value, $meta_id, $args=array()) {
        parent::__construct($box_label, $box_value, $meta_id,$args);
        $this->inline_display = !(!empty($this->args) && (empty($this->args['inline']) || $this->args['inline'] == false ));
        if(!empty($this->args) && (empty($this->args['inline']) || $this->args['inline'] == false ) ) {
            $this->inline_display = false;
        }
    }

    public function render_metabox_html(){
        ?>
        <p>
        <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e( $this->box_label,  REWORLDMEDIA_TERMS ); ?></label>
        <?php
        if(!empty($this->args) && !empty($this->args['options'])) {
            foreach($this->args['options'] as $val){ 
                if ($this->inline_display){
                    echo "<br>";
                }
                ?>
               
                    <input type="radio" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" 
                        value="<?php  echo $val; ?>" <?php echo (($this->box_value == $val) ? "checked" : ""); ?> />
                    <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e( $val,  REWORLDMEDIA_TERMS ); ?></label>
               
                <?php
            }
        }
        ?>
        </p>
        <?php
    }
}
class Select_MetaBox_Type extends MetaBox_Type
{
    public function __construct($box_label, $box_value, $meta_id, $args=array()) {
        $this->box_label = $box_label;
        $this->box_value = $box_value;
        $this->meta_id = $meta_id;
        $this->args = $args;

        if(!empty($this->args) && empty($this->box_value) && !empty($this->args['default'])){
            if(is_array($this->args['default'])){ 
                $tab_filtre = array();
                foreach($this->args['default'] as $value){
                    if(in_array($value, $this->args['options']) && !in_array($value, $tab_filtre)){
                        $tab_filtre[]= $value;
                    }
                }
                $this->box_value=$tab_filtre;
            }else { //$this->args['default'] n'est pas an array
                if(in_array($this->args['default'], $this->args['options'])){
                    $this->box_value = $this->args['default'];
                }       
            }
        }
    }

    public function render_metabox_html(){
        ?>
        <p>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                let is_multi = <?php echo (isset($this->args['multiselect'])) ? json_encode($this->args['multiselect']) : json_encode(false) ; ?> ;  
                let nbr_options = <?php echo (isset($this->args['options'])) ? json_encode(count($this->args['options'])) : json_encode(0) ; ?> ;
                let selected_options = <?php echo (!empty($this->box_value)) ? json_encode($this->box_value) : json_encode(array()) ; ?> ;
                let is_table = Array.isArray(selected_options);
                let select_id = <?php echo $this->meta_id; ?>;
                
                if(!is_table) {
                    selected_options = new Array(selected_options);
                }
                if(is_multi){
                    $(select_id).selectize({
                        maxItems: 100,
                        items: selected_options
                    });
                }else {
                    $(select_id).selectize({
                        create: false,
                        sortField: "text",
                        items: selected_options
                    });
                }  
            });
           
        </script>
        
        <?php
        if(!empty($this->args) && !empty($this->args['options'])) {           
            ?>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e( $this->box_label,  REWORLDMEDIA_TERMS ); ?></label>
            <select style="width:100%" class="multi_select" name="<?php echo $this->meta_id; ?>[]" id="<?php echo $this->meta_id; ?>" value= "<?php echo $this->box_value;?>">
            <?php if(empty($this->box_value)){
                ?>
                    <option value="">Veuillez Choisir</option>
                <?php
                }
            ?>
            <?php
            if(isset($this->args['is_options_array']) && $this->args['is_options_array']){
                foreach($this->args['options'] as $val){ 
                ?>
                    <option value="<?php  echo $val['value']; ?>"><?php  echo $val['label']; ?></option>
                <?php
                }
            }
            else {
                foreach($this->args['options'] as $val){ 
                    ?>
                        <option value="<?php  echo $val;?>"><?php  echo $val; ?></option>
                    <?php
                }
            }
            ?>
            </select>
        <?php
        }
        ?>
        </p>
        <?php
    }
}
class Number_MetaBox_Type extends MetaBox_Type
{

    public function __construct($box_label, $box_value, $meta_id, $args = array())
    {
        parent::__construct($box_label, $box_value, $meta_id, $args);
    }

    public function render_metabox_html()
    {
        $max=$this->args["max_value"]??10;
        $this->box_value? $value=$this->box_value: $value=0;
      
?>
        <p>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br />
            <input type="number" max="<?php echo $max ?>" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" value="<?php echo $value ?>" style="width:100%" />
        </p>
    <?php
    }
}

class CodeEditor_MetaBox_Type extends MetaBox_Type
{

	public function __construct($box_label, $box_value, $meta_id, $args = array())
	{
		parent::__construct($box_label, $box_value, $meta_id, $args);
	}

	public function render_metabox_html()
	{
		$this->box_value? $value=$this->box_value: $value=0;

		?>
        <p>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e($this->box_label,  REWORLDMEDIA_TERMS); ?></label>
            <br/>
            <textarea   name="<?php echo $this->meta_id; ?>"
                        id="<?php echo $this->meta_id; ?>" style="width:100%"
            ><?php echo $value ?></textarea>
            <script>
                // add code CodeMirror to textarea by id
               jQuery(document).ready(function($) {
                   $("textarea#<?php echo $this->meta_id; ?>").get(0);
                   console.log($("#<?php echo $this->meta_id; ?>").val(js_beautify($("textarea#<?php echo $this->meta_id; ?>").text())) );
                   var <?php echo $this->meta_id; ?> = CodeMirror.fromTextArea(
                       document.getElementById("<?php echo $this->meta_id; ?>"),
                       {
                           value: js_beautify($("textarea#<?php echo $this->meta_id; ?>").text()),
                           lineNumbers: true,
                           mode: "application/json",
                           styleActiveLine: true,
                           matchBrackets: true,
                           autoCloseBrackets: true,
                           lineWrapping: true,
                           theme: "material"
                   });
                });
            </script>
        </p>
		<?php
	}
}
class SingleIMG_Select_MetaBox_Type extends MetaBox_Type
{
    public function __construct($box_label, $box_value, $meta_id, $args=array()) {
        $this->box_label = $box_label;
        $this->box_value = $box_value;
        $this->meta_id = $meta_id;
        $this->args = $args;
    }

    public function render_metabox_html(){
        if(is_array($this->box_value)){
            $this->box_value = $this->box_value[0];
        }
        ?>
        <p>
        <?php
        if(!empty($this->args) && !empty($this->args['options'])) {
            ?>
            <label for="<?php echo $this->meta_id; ?>" style="width:100%"><?php _e( $this->box_label,  REWORLDMEDIA_TERMS ); ?></label>
            <select style="width:100%" name="<?php echo $this->meta_id; ?>" id="<?php echo $this->meta_id; ?>" value= "<?php echo $this->box_value;?>">
            <?php if(empty($this->box_value)){
                ?>
                    <option value="">Veuillez Choisir</option>
                <?php
                }
            ?>
            <?php
            if(isset($this->args['is_options_array']) && $this->args['is_options_array']){
                foreach($this->args['options'] as $val){ 
                ?>
                    <option value="<?php  echo $val['value']; ?>"> <?php echo $val['label']; ?></option>
                <?php
                }
            }
            else {
                
                foreach($this->args['options'] as $val){ 
                    if(!empty($this->box_value)){
                        ?>
                        <option value="<?php  echo $val;?>" <?php echo (strcmp($val,$this->box_value) == 0) ? "selected": ""; ?>><?php  echo $val; ?></option>
                        <?php

                    }else{ 
                    ?>
                        <option value="<?php  echo $val;?>"><?php  echo $val; ?></option>
                    <?php
                    }
                }
            }
            ?>
            </select>
            
        <?php
        if(!empty($this->box_value) && isset($this->args['category'])){
            $src_svg = AF_THEME_DIR_URI.'/assets/img/af-biblio/'.$this->args['category'].'/'.$this->box_value;
            ?>
            <img style="width: 150px; border: 2px solid #000; margin: 10px;"  
                src=<?php echo $src_svg;?> 
            />
            <?php
        }
        }
        ?>
        </p>
        <?php
    }
}