<?php 


add_filter('enable_bandeau_partners','activer_bandeau_partners_pms', 2 ,1);
add_filter( 'newsletter_qualif' ,  'default_newsletter_qualif', 1 , 1 );


function default_newsletter_qualif($return=false){
	return RW_Hooks::default_newsletter_qualif($return);
}

function activer_bandeau_partners_pms($bandeau_is_enabled){
    //disactiver le bandeau de partenaire quand c'est une page sans Sidebar
    // à décommenter en Phase 2
    // return (strpos(get_page_template(),"page-no-sidebar.php") !== false)?false:true;
    return false;
}

// add_action("wp_footer","add_bandeau");

// function add_bandeau(){
//     echo '<script type="text/javascript">window.gdprAppliesGlobally=true;(function(){function a(e){if(!window.frames[e]){if(document.body&&document.body.firstChild){var t=document.body;var n=document.createElement("iframe");n.style.display="none";n.name=e;n.title=e;t.insertBefore(n,t.firstChild)} else{setTimeout(function(){a(e)},5)}}}function e(n,r,o,c,s){function e(e,t,n,a){if(typeof n!=="function"){return}if(!window[r]){window[r]=[]}var i=false;if(s){i=s(e,t,n)}if(!i){window[r].push({command:e,parameter:t,callback:n,version:a})}}e.stub=true;function t(a){if(!window[n]||window[n].stub!==true){return}if(!a.data){return} var i=typeof a.data==="string";var e;try{e=i?JSON.parse(a.data):a.data}catch(t){return}if(e[o]){var r=e[o];window[n](r.command,r.parameter,function(e,t){var n={};n[c]={returnValue:e,success:t,callId:r.callId};a.source.postMessage(i?JSON.stringify(n):n,"*")},r.version)}} if(typeof window[n]!=="function"){window[n]=e;if(window.addEventListener){window.addEventListener("message",t,false)}else{window.attachEvent("onmessage",t)}}}e("__tcfapi","__tcfapiBuffer","__tcfapiCall","__tcfapiReturn");a("__tcfapiLocator");(function(e){ var t=document.createElement("script");t.id="spcloader";t.type="text/javascript";t.async=true;
//         t.src=""
//         n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n)})("745a135f-908d-4bfb-beca-56b93e25dc45")})();</script>'
// }

function get_youtube_embed_url($url)
{
     $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}

add_shortcode("fyoutube","get_youtube_iframe");

function get_youtube_iframe($attrs){
    $url = $attrs["url"];
    $width = $attrs["width"];
    $height = $attrs["height"];
    $autoplay = $attrs["autoplay"];
    $loop = $attrs["loop"];
    if(!empty($url)){
        $url = get_youtube_embed_url($url);
        $widthhtm = '';
        $heighthtm = '';
        if(!empty($width)) {
            $widthhtm = " width=\"$width\" ";
        }
        if(!empty($height)) {
            $heighthtm = " height=\"$height\" ";
        }
        echo '<iframe '.$width.' '.$width.'  src="'.$url.'" style="height:300px;width:100%;border:none;overflow:hidden;"></iframe>';
    }
}
