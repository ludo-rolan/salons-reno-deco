<?php
class cmp_didomi extends rw_partner {

	/**
	 * Ce script est inséré sur l’ensemble des pages
	 *
	 * */


	function cmp_didomi_implementation(){
		

		add_filter('dfp_gpt_custom_script', [$this,'dfp_gpt_custom_script']);
		
			$script = <<<DIDOMI

			<link rel="preconnect" href="//sdk.privacy-center.org" />

			<script type="text/javascript">window.gdprAppliesGlobally=true;(function(){function a(e){if(!window.frames[e]){if(document.body&&document.body.firstChild){var t=document.body;var n=document.createElement("iframe");n.style.display="none";n.name=e;n.title=e;t.insertBefore(n,t.firstChild)}
			else{setTimeout(function(){a(e)},5)}}}function e(n,r,o,c,s){function e(e,t,n,a){if(typeof n!=="function"){return}if(!window[r]){window[r]=[]}var i=false;if(s){i=s(e,t,n)}if(!i){window[r].push({command:e,parameter:t,callback:n,version:a})}}e.stub=true;function t(a){if(!window[n]||window[n].stub!==true){return}if(!a.data){return}
			var i=typeof a.data==="string";var e;try{e=i?JSON.parse(a.data):a.data}catch(t){return}if(e[o]){var r=e[o];window[n](r.command,r.parameter,function(e,t){var n={};n[c]={returnValue:e,success:t,callId:r.callId};a.source.postMessage(i?JSON.stringify(n):n,"*")},r.version)}}
			if(typeof window[n]!=="function"){window[n]=e;if(window.addEventListener){window.addEventListener("message",t,false)}else{window.attachEvent("onmessage",t)}}}e("__tcfapi","__tcfapiBuffer","__tcfapiCall","__tcfapiReturn");a("__tcfapiLocator");(function(e){
				var t=document.createElement("script");t.id="spcloader";t.type="text/javascript";t.src="https://sdk.privacy-center.org/"+e+"/loader.js?target="+document.location.hostname;t.charset="utf-8";
		        
				var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n)})("745a135f-908d-4bfb-beca-56b93e25dc45")})();</script> 

DIDOMI;

			echo $script ;
	}



	function dfp_gpt_custom_script($custom_script){
		//check here please : https://support.didomi.io/en/articles/4314605-setup-google-ad-manager-in-the-tcf

		if(is_dev('chagement_didomi_2_1_9788')) {
			$custom_script = '
				<link rel="preload" href="https://securepubads.g.doubleclick.net/tag/js/gpt.js" as="script">
				<script type="didomi/javascript" data-loading="immediate" async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"  onload= "console.log(\'debug load doubleclick\');"  ></script>';
		}

		if(is_dev('chagement_didomi_2_1_9788_v2')) {
	        		$custom_script = '

	<script type="didomi/javascript"  data-vendor="didomi:google" async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"  onload= "console.log(\'debug load doubleclick\');"  ></script>';

	        	}

		return $custom_script;
	}



}