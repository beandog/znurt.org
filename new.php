<?

	if(!$page)
		$page = 1;
	
	if($arch && !in_array($arch, $arr_arch))
		$arch = null;
		
	// Select arch
	$class = "";
	if(!$arch)
		$class = "active";
		
	echo "<div id='sortBy'>\n";
	$str = gettext('SORT BY PLATFORM');
	echo "\t<h4 style='margin-left: 0;'>$str</h4>\n";
	echo "\t\t<ul>\n";
	$str = gettext('all platforms');
	echo "\t\t\t<li class='first'><a href='$base_uri' class='$class'>$str</a></li>\n";
	
	foreach($arr_display_arch as $name) {
		$class = "";
		if($name == $arch)
			$class = 'active';
		
		if($name == end($arr_display_arch))
			$li = "last";
		else
			$li = "";
		
		$url = $base_uri.'arch/'.$name;
		
		echo "\t\t\t<li class='$li'><a href='$url' class='$class'>$name</a></li>\n";
	}
	echo "\t\t</ul>\n";
	echo "\t<div class='clear'></div>\n";
	echo "</div>\n";
	
	if($page)
		$offset = $amount * ($page - 1);
	
	$arr = recentPackages($amount, $offset, $arch);
	
// 	Common::pre($arr);
	
	if(count($arr))
 		foreach($arr as $row)
 			$arr_packages[$row['package']][] = $row['ebuild'];
 	
//  	Common::pre($arr_packages);
	
	if(count($arr_packages)) {
		foreach($arr_packages as $arr) {
			echo keywordsRow($arr, 'new');
		}
	}
			
	// $page is the current page we are on, so default is 1.
	
	$base = $base_uri;
	if($arch)
		$base .= "arch/$arch/";
	
	$next_page = $base.($page + 1);
	$prev_page = $base.($page - 1);
		
	echo "<div id='pagination' style='padding-bottom: 25px;'>\n";
	echo "<img src='".$base_uri."img/hr_dotted.png' width='709' height='3' alt=''>\n";
	if($page) {
		$str = gettext('Next Page');
		echo "<a href='$next_page'><img src='".$base_uri."img/next_page.png' width='125' height='25' alt='$str'></a>\n";
	}
	if($page > 1) {
		$str = gettext('Previous Page');
		echo "<a href='$prev_page'><img src='".$base_uri."img/prev_page.png' width='125' height='25' alt='$str'></a>\n";
	}
	echo "</div>\n";
	
?>