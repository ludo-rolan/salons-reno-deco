<?php

class Art19 extends rw_partner {

   function art19_implementation($attrs) {
   	   global $post ; 
	   $key='';
	   $add_id_scrolling_art19 = !empty($this->get_param('add_id_scrolling_art19')) ? $this->get_param('add_id_scrolling_art19') : false;
	   $disable_autoplay = get_param_global('art19_disable_autoplay',false);
	   if (empty($attrs['auto_play'])) {
	   	   $disable_autoplay = true;
	   }
	   $value='';
	   if (isset($attrs['series_id'])) {
		   $series_id = (!empty($attrs['series_id'])) ? $attrs['series_id'] : '70da7896-4443-4fdb-a95c-ff675f2b65fa';
		   $key='series-id';
		   $value= $series_id;
	   }else if (isset($attrs['episode_id'])) {
			$episode_id = (!empty($attrs['episode_id'])) ? $attrs['episode_id'] : '';
			$key='episode-id';
			$value= $episode_id;
		}
		$posts_exclude = !empty($attrs['posts_exclude']) ? explode(',', $attrs['posts_exclude']) : [];
		if (!empty($key) && !empty($value) && !in_array($post->ID,$posts_exclude)) {
			if($add_id_scrolling_art19){
				echo "<div id='art19-player'></div>";
			}
		$script = <<<ART19
		<link href="https://web-player.art19.com/assets/current.css" media="screen" rel="stylesheet" type="text/css">
		<script src="https://web-player.art19.com/assets/current.js" type="text/javascript"></script>
		<div class="art19-web-player awp-medium awp-theme-dark-blue" data-$key="$value" data-pick-from-series="latest"></div>

ART19;
		if(!$disable_autoplay){
					$script .= <<<ART19
<script type="text/javascript">
const divPodcast = document.querySelector('.art19-web-player');
const podcastRetry = setInterval(podcastPlay, 2000);

function podcastPlay(){
	var playButton = divPodcast.querySelector('.art19-web-player .awp-icon-play' );
	if (playButton !== null){
		playButton.click();
		clearInterval(podcastRetry);
	}
}
</script>
ART19;
		}
	}
		return $script;
	}
}
