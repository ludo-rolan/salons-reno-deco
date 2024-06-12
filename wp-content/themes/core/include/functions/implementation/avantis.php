<?php


class avantis extends rw_partner {
  /**
     * Function for Callback
     */
  function avantis_implementation(){
    $avantis_script = '<script async src="https://cdn.avantisvideo.com/avm/js/video-loader.js?id=37a16c48-87e3-4341-b9ab-c7c9469fbf98&tagId=1&subId=&callback=" id="avantisJS"> </script>';
    echo $avantis_script;
  }
}