<?
	if(!$nocache) {
	
		$data .= ob_get_contents();
	
		$cache->save($data);
		ob_end_flush();
	}
?>