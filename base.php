<?php

	require_once 'inc.header1.php';

	$arr_package_sections = array('changelog', 'bugs', 'dependencies', 'downloads', 'license', 'useflags');
	$arr_ebuild_sections = array('dependencies', 'downloads', 'license',  'source', 'useflags');

 	$str = rawurldecode($_SERVER['REQUEST_URI']);

	if($base_uri != "/")
		$str = str_replace($base_uri, "", $str);

	// FIXME should rely on this function
	$arr_url = parse_url($_SERVER['REQUEST_URI']);

	if($arr_url['query'])
		$str = str_replace("?".$arr_url['query'], "", $str);

	$arr = explode("/", $str);

	foreach($arr as $value) {
		$value = trim($value);
		if(!empty($value))
			$uri[] = $value;
	}

	$file = null;

	if(count($uri) == 0) {

		$body = 'home';
		$page = $uri[0];
		$view = 'new';
		$file = "new.php";

	} elseif(count($uri) == 1) {

		if(is_numeric($uri[0])) {

			$body = 'home';
			$page = $uri[0];
			$view = 'new';
			$file = "new.php";

		} else {

			switch($uri[0]) {

				case 'about':
				case $url_about:
					$file = 'about.php';
					$html_title = gettext('the fresh ebuilds').' ~ '.gettext('about');
					break;

				case 'arch':
				case $url_arch:
					$nocache = true;
					$body = "arch";
					$file = "architectures.php";
					$html_title = gettext("architectures");
					break;

				case 'categories':
				case $url_categories:
					$body = 'categories';
					$view = 'category';
					$file = 'categories.php';
					$html_title = gettext("categories");
					break;

				case 'bugs':
					$file = 'bugs.php';
					$html_title = gettext('the fresh ebuilds').' ~ '.gettext('bugs');
					break;

				case 'es':
					$lingua = 'es';
					$locale = "es_US";
					$body = 'home';
					$view = 'new';
					$file = "new.php";
					break;

				case 'feeds':
				case $url_feeds:
					$file = "feeds.php";
					$html_title = gettext("xml feeds");
					break;

				case 'licenses':
					$body = 'licenses';
					$file = "licenses.php";
					$html_title = gettext("licenses");
					break;

				case 'linguas':
				case $url_linguas;
					$body = 'linguas';
					$file = "linguas.php";
					$html_title = gettext("linguas");
					break;

				case 'new_ebuilds':
					$body = 'new_ebuilds';
					$file = 'new_ebuilds.php';
					$html_title = 'new ebuilds';
					break;

				case 'new_packages':
				case $url_new_packages:
					$body = 'new_packages';
					$file = 'new_packages.php';
					$html_title = gettext('new packages');
					break;

				case 'search':
					$file = "search.php";
					$html_title = gettext("advanced search");
					break;

				case 'useflags':
				case $url_useflags:
					$body = 'useflags';
					$file = "useflags.php";
					$html_title = gettext("use flags");
					break;

				case 'dev':
					$file = 'dev.php';
					$html_title = gettext('development');
					break;
			}
		}

		if(!$file) {
			$sql = "SELECT name, id FROM category;";
			$categories = $db->getAssoc($sql);

			if(count($categories) && in_array($uri[0], array_keys($categories))) {
				$category_id = $categories[$uri[0]];
				$view = 'category';
				$file = "category.php";
				$html_title = $uri[0];
			}
		}

	} elseif((count($uri) == 2 && !$file) || (count($uri) == 3 && $uri[2] == "xml")) {

		switch($uri[0]) {

			case 'arch':
				$body = 'home';
				$arch = $uri[1];
				$view = 'new';
				$file = "new.php";
				$html_title = $uri[1]." ".gettext("architecture");
				break;

			case 'licenses':
				$file = "license.php";
				$html_title = gettext("licenses");
				$license_name = $uri[1];
				break;

			case 'new_ebuilds':
				$body = 'new_ebuilds';
				$file = 'new_ebuilds.php';
				$html_title = gettext('new ebuilds');
				$page = $uri[1];
				break;

			case 'new_packages':
				$body = 'new_packages';
				$file = 'new_packages.php';
				$html_title = gettext('new packages');
				$page = $uri[1];
				break;

			case 'search':
				$file = "search.php";
				$html_title = gettext("search");
				break;

			case 'useflags':
				$sql = "SELECT COUNT(1) FROM use WHERE name = ".$db->quote($uri[1]).";";
				$count = $db->getOne($sql);
				if($count) {
					$useflag_name = $uri[1];
					$file = "useflag.php";
					$html_title = gettext("use flags")." ~ ".$uri[1];
				} else {
					$file = "useflags.php";
					$html_title = gettext("use flags");
				}
				break;
		}

		if(!$file) {
			// Check to see if its a package
			$sql = "SELECT package FROM ebuilds WHERE category_name = ".$db->quote($uri[0])." AND package_name = ".$db->quote($uri[1])." LIMIT 1;";
			$package_id = $db->getOne($sql);

			if($package_id) {
				$view = 'package';
				$file = 'package.php';

				$html_title = $uri[0]." ~ ".$uri[1];
			}

		}

	} elseif(count($uri) == 3 && !$file) {

		if(in_array($uri[2], $arr_package_sections)) {

			// Check to see if its a package
			$sql = "SELECT package FROM ebuilds WHERE category_name = ".$db->quote($uri[0])." AND package_name = ".$db->quote($uri[1])." LIMIT 1;";
			$package_id = $db->getOne($sql);

			if($package_id) {
				$view = 'package';
				$file = 'package.php';
				$section = $uri[2];
				$html_title = $uri[0]." ~ ".$uri[1];
			}

		}

		if($uri[0] == "arch" && is_numeric($uri[2])) {
			$arch = $uri[1];
			$page = $uri[2];
			$view = 'new';
			$file = "new.php";

			$html_title = $uri[1]." ".gettext("architecture");

		}

		if(!$file) {
			// Check to see if its a package
			$sql = "SELECT id AS ebuild_id, package AS package_id FROM ebuilds WHERE category_name = ".$db->quote($uri[0])." AND package_name = ".$db->quote($uri[1])." AND pf = ".$db->quote($uri[2]).";";
			$row = $db->getRow($sql);

			if(is_array($row)) {
				$view = 'ebuild';
				extract($row);
				$file = 'ebuild.php';
				$html_title = $uri[0]." ~ ".$uri[2];
			}
		}

	} elseif(count($uri) == 4 && !$file) {

		if(in_array($uri[3], $arr_ebuild_sections)) {
			// Check to see if its a package
			$sql = "SELECT id AS ebuild_id, package AS package_id FROM ebuilds WHERE category_name = ".$db->quote($uri[0])." AND package_name = ".$db->quote($uri[1])." AND pf = ".$db->quote($uri[2]).";";
			$row = $db->getRow($sql);

			if(is_array($row)) {
				$view = 'ebuild';
				extract($row);
				$file = 'ebuild.php';
				$html_title = $uri[0]." ~ ".$uri[2];
				$section = $uri[3];
			}
		}

	}

	if($file) {

		if(substr(php_sapi_name(), 0, 3) == 'cgi') {
			header('Status: 200', TRUE);
		} else {
			header("HTTP/1.1 200 OK", true, 200);
		}

// 		if(!$xml) {

			if($lingua != "en")
				require 'inc.i18n.php';

			require_once 'inc.header2.php';
			require_once 'inc.header3.php';
			require_once 'inc.content1.php';
			require_once($file);
// 		}


	} else {

		// Will return 404
		require_once 'inc.header2.php';
		require_once 'inc.header3.php';
		require_once 'inc.content1.php';

		echo "<h4>Um, whut?</h4>";

		echo "<div align='center'><img src='".$base_uri."images/failboat.jpg'></div>\n";

	}

	if(!$xml)
		require_once 'inc.content2.php';

?>
