<?

	$hostname = php_uname('n');
	
	switch($hostname) {
	
		case 'charlie':
		
			$znurt = false;
			$include_path = ":/home/steve/php/inc:/home/steve/svn/portage";
			$mdb2 = "mdb2/charlie.portage.php";
			
			$base_uri = "/~steve/sp/gentoo/znurt/";
			$base_url = "http://localhost".$base_uri;

			break;
		
		case 'rom':
		
			$znurt = false;
			$include_path = ":/home/steve/php/inc:/home/steve/svn/portage";
			$mdb2 = "mdb2/rom.portage.php";
			
			$base_uri = "/";
			$base_url = "http://znurt.org".$base_uri;
		
			break;
		
		case 'tenforward':
		
			$znurt = true;
			$include_path = ":/var/www/znurt.org/inc:/var/www/znurt.org/portage";
			$mdb2 = "mdb2/tenforward.portage.php";
			
			$base_uri = "/";
			$base_url = "http://znurt.org".$base_uri;
		
			break;
		
		case 'alan-one':
		case 'znurt':
		
			$znurt = true;
			$include_path = ":/var/www/znurt.org/inc:/var/www/znurt.org/portage";
			$mdb2 = "mdb2/alan-one.portage.php";
			
			$base_uri = "/";
			$base_url = "http://znurt.org".$base_uri;
		
			break;
			
		case 'willy':
		case 'dumont':
			
			$znurt = true;
			$include_path = ":/home/znurt/php/inc:/var/znurt";
			$mdb2 = "mdb2/dumont.portage.php";
			
			$base_uri = "/";
			$base_url = "http://znurt.org".$base_uri;
			
			break;
	
	}
	

	if($include_path) {
		ini_set('include_path', ini_get('include_path').$include_path);
		
		require_once $mdb2;
		require_once 'class.common.php';
		require_once 'class.shell.php';
	}

	ini_set('include_path', ini_get('include_path').":/home/steve/php/inc:/home/steve/svn/portage");
	
?>
