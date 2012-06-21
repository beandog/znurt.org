<?
	
	require_once 'inc.header1.php';
	
	if($_REQUEST['search']) {
		die;
	}
	
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';
	
	$q = trim($_GET['q']);
	
	// Check to see if it's a bot
	
	
// 	if($q && $_GET) {
// 	
// 		$url = $base_uri."search/$q";
// 		header("Location: $url");
// 		die;
// 	}
// 	
// 	else {
	
		// Advanced search
		if(count($uri) == 1) {
		
			$str = gettext("ADVANCED SEARCH");
			echo "<h4>$str</h4>";
			
		} else {
	
// 			$q = $uri[1];
		
			$offset = 0;
	
			$query = $db->quote("%$q%");
			$name = $db->quote($q);
	
			// Use like (case insensitive) for now, should be less of a load on db
			$sql = "SELECT * FROM search_ebuilds e WHERE cp ~~* $query OR description ~~* $query OR package_name ~~* $query OR ebuild_name ~~* $query OR atom ~~* $query ORDER BY $name = ebuild_name DESC, $name = package_name DESC, package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC;";
			
			$arr = $db->getAll($sql);
			
			$arr_packages = array();
			
			if(!PEAR::isError($arr)) {
				if(count($arr))
					foreach($arr as $row)
						$arr_packages[$row['package']][] = $row['ebuild'];
				
				// FIXME redirect to the page instead.
				if(count($arr_packages) == 1) {
					$package_id = key($arr_packages);
					require_once 'package.php';
					require_once 'inc.content2.php';
					break;
				}
				
				$arr_easter_eggs = array(
					'torrent' => 'funny-pictures-pirate-cat-grimaces.jpg',
					'google' => 'o-hai-googlz-i-can-has-privacy.jpg',
					'fail' => 'fail_cat.jpg',
					'secret' => 'funny-pictures-cat-activates-secret-door.jpg',
					'flameeyes' => 'funny-pictures-cat-loves-coffee.jpg',
					'araujo' => 'funny-pictures-cat-will-destroy-your-work.jpg',
					'bonsaikitten' => 'cat_minions.jpg',
					'mr_bones_' => 'funny-pictures-kitten-kills-with-his-eye.jpg',
					'nightmorph' => 'astrocat.jpg',
					'vader' => 'funny-pictures-darth-vader-cat.jpg',
					'drobbins' => 'dune-cat.jpg',
					'beandog' => 'ubeantoo.jpg',
					'antarus' => 'ee_antarus.jpg',
					'darkside_' => 'ee_darkside_.jpg',
				);
				
				$count = count($arr_packages);
				
				if($count < 100) {
					$str = sprintf(gettext('SEARCH RESULTS FOR %1$s &nbsp; (%2$u)'), "\"$q\"", $count);
					echo "<h4>$str</h4>";
				}
					
				if($arr_easter_eggs[strtolower($q)] && $offset == 0) {
					$url = $base_uri."images/".$arr_easter_eggs[$q];
					echo "<div align='center' style='padding: 15px;'><img src='$url'></div>\n";
				}
				
				if($count > 1) {
				
					$str = "";
					
					// Only display the first 100
					
					if($count > 100) {
						$str = sprintf(gettext('TOP (%1$u) SEARCH RESULTS FOR %2$s &nbsp; (%3$u)'), 100, "\"$q\"", $count);
						echo "<h4>$str</h4>";
						$arr_chunk = array_chunk($arr_packages, 100, true);
						$arr_packages = current($arr_chunk);
					}
					
					foreach($arr_packages as $arr)
						echo keywordsRow($arr, 'search');
				
				}
			}
			
			elseif(PEAR::isError($arr)) {
			
				$str = sprintf(gettext('SEARCH RESULTS FOR %1$s &nbsp; (%2$u)'), $q, count($arr_packages));
				echo "<h4>$str</h4>";
			
				$str = gettext("Hmm, that query didn't work.");
				echo "<p>$str</p>";
			
			}
			
		}
		
// 	}

	require_once 'inc.content2.php';
	
	
?>
