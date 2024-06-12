<?php
/*TME - BUG - DIAPO (4403)*/
if(isset($_POST['action']) && $_POST['action'] == 'query-attachments'){
	if(empty($_GET['lang'])){
		$url = parse_url($_SERVER['HTTP_REFERER']);
		parse_str($url['query'], $query);
		if(!empty($query['lang'])){
			$_GET['lang'] = $query['lang'];
		}
	}
}
/*end #4403*/