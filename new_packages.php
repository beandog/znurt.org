<?
	if(!$page)
		$page = 1;
	
	echo "<h4>".gettext("New Packages")."</h4>";
	
	if($page)
		$offset = $amount * ($page - 1);
	
 	// FIXME is this even right?
 	$sql = "SELECT p.id AS package, e.id AS ebuild FROM package p LEFT OUTER JOIN ebuilds e ON e.package = p.id WHERE p.idate > '2010-01-04 12:00:00.0-07' AND p.portage_mtime IS NOT NULL AND e.id IS NOT NULL ORDER BY p.idate DESC, e.cache_mtime DESC, e.category_name, e.package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC LIMIT $amount OFFSET $offset;";
	
	// FIXME allow arch
// 	$sql = "SELECT e.package, e.id AS ebuild FROM ebuild e INNER JOIN package_recent pr ON e.package = pr.package AND e.cache_mtime = pr.max_ebuild_mtime INNER JOIN package p ON e.package = p.id WHERE e.status = 0 AND e.package IN (SELECT package FROM package_recent ORDER BY max_ebuild_mtime DESC, package LIMIT $amount OFFSET $offset) ORDER BY p.idate DESC, pr.max_ebuild_mtime DESC, e.package, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC;";
	
	$arr = $db->getAll($sql);
	
	foreach($arr as $row) {
		$arr_new_packages[$row['package']][] = $row['ebuild'];
	}
	
	if(count($arr_new_packages)) {
		foreach($arr_new_packages as $arr) {
			echo keywordsRow($arr, 'new');
		}
	}
			
	// $page is the current page we are on, so default is 1.
	
	$base = $base_uri."new_packages/";
	if($arch)
		$base .= "arch/$arch/";
	
	$next_page = $base.($page + 1);
	$prev_page = $base.($page - 1);
		
	echo "<div id='pagination' style='padding-bottom: 25px;'>\n";
	echo "<img src='".$base_uri."img/hr_dotted.png' width='709' height='3' alt=''>\n";
	if($page)
		echo "<a href='$next_page'><img src='".$base_uri."img/next_page.png' width='125' height='25' alt='Next Page'></a>\n";
	if($page > 1)
		echo "<a href='$prev_page'><img src='".$base_uri."img/prev_page.png' width='125' height='25' alt='Previous Page'></a>\n";
	echo "</div>\n";
	
?>