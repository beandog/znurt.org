<?

	if(!$nocache) {
		require_once 'Cache/Lite.php';
		
		$cache_id = md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		
		if($_COOKIE)
			$cache_id = md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . md5(implode(" ", array_keys($_COOKIE))));
		
		// Use /tmp/znurt if we can hack it
		if((!is_dir("/tmp/znurt") && mkdir("/tmp/znurt")) || (is_dir("/tmp/znurt") && is_writable("/tmp/znurt")))
			$cache_tmp_dir = "/tmp/znurt/";
		else
			$cache_tmp_dir = "/tmp/";
		
		$cache_options = array(
			'cacheDir' => $cache_tmp_dir,
			'lifeTime' => 3600,
		);
		
		$cache = new Cache_Lite($cache_options);
		if($znurt && $data = $cache->get($cache_id)) {
			echo $data;
			die;
		}
		
		// Start caching if on live site
		if($znurt && !$nocache) {
			// Start caching
			ob_start();
		}
	}
	
?>