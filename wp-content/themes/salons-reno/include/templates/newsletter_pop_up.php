
<?php
$img_desktop = wp_get_attachment_image_src(get_option('promo_option_img_1') ,'large');

$img_mobile = wp_get_attachment_image_src(get_option('promo_option_img_2'), 'large');
?>

<style>
  .promo {
    background-image:url('<?php echo $img_mobile[0]; ?>');

  }    
@media only screen and (min-width: 786px) {
  .promo {
    background-image:url('<?php echo $img_desktop[0]; ?>');

  }
}

</style>
<!-- Modal -->



<a type="button"  style="display: none;" id="promo_up_ad_button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#flipFlop">
Click Me
</a>


<!-- The modal -->
<div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-center justify-content-center pop_up_modal text-center" role="document">
<div class=" promo modal-content">
<div class="row ">
                <div class="col-10">
                </div>
                <div class="col-2">
                    <button type="button" class="promo_close close" data-dismiss="modal"  aria-label="Close">X</button>
                </div>
            </div>
            <div class="row promo_footer justify-content-center text-center ">
   <a class="promo_btn" href=" <?php echo  get_option('promo_option_cta_link') ?>">
    <?php echo get_option('promo_option_cta_title');  ?>
  </a>
</div>
</div>
</div>

</div>

