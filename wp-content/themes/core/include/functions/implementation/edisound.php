<?php

class Edisound extends rw_partner
{
    public function edisound_implementation($attrs)
    {
        $affiche = true;

        if($affiche){
            if (!empty($attrs['placement_id']) && !empty($attrs['data_pid'])) {
                $placementid = $attrs['placement_id'];
                $data_pid = $attrs['data_pid'];
                $data_gid = (!empty($attrs['data_gid']))?$attrs['data_gid']:"1eb54159-e1aa-6848-b0e9-bb8d1c0110e1";
                
                $script = <<<EDISOUND
<div class="rwm-podcast-player" data-placementid="$placementid" data-pid="$data_pid" data-gid="$data_gid"></div>
<script type="text/javascript" src="https://publishers.edisound.com/player/javascript/init.js"></script>
EDISOUND;

                return $script;
            }
        }
    }
}
