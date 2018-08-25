<?php

	if(!$page)
		$page = 1;

	echo "<h4>New Ebuilds</h4>";

	if($page)
		$offset = $amount * ($page - 1);

	$sql = "SELECT e.package, e.id AS ebuild FROM ebuilds e WHERE udate IS NULL AND idate > '2010-01-04 12:00:00.0-07' ORDER BY idate DESC, e.cache_mtime DESC, e.category_name, e.package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC LIMIT $amount OFFSET $offset;";
	$arr = $db->getAll($sql);

	foreach($arr as $row) {
		$arr_new_ebuilds[$row['package']][] = $row['ebuild'];
	}

	if(count($arr_new_ebuilds) < 100) {
		echo "<div class='description'><b>Note:</b> The new ebuilds data will be a bit sparse while the database is still populating new data after the site launch.</div>";
	}

	if(count($arr_new_ebuilds)) {
		foreach($arr_new_ebuilds as $arr) {
			echo keywordsRow($arr, 'new');
		}
	}

	// $page is the current page we are on, so default is 1.

	$base = $base_uri."new_ebuilds/";
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
