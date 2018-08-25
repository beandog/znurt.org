<?php

	require_once 'class.db.category.php';

	if($category_id) {

		$obj = new DBCategory($category_id);

		$name = $obj->name;
		$description = $obj->getDescription($lingua);

		$url = $base_uri."categories";

		echo "<h4>$name</h4>";

		if($description)
			echo "<div class='description'>$description</div>";

		$sql = "SELECT package_name, package, id AS ebuild FROM ebuilds WHERE category = ".$db->quote($category_id).";";
		$arr = $db->getAll($sql);

		foreach($arr as $row) {
			extract($row);
			$arr_packages[$package][] = $ebuild;
			$arr_name[$package] = $package_name;
		}

		foreach($arr_packages as $package => $arr) {
 			if(count($arr)) {

 				$e = new DBEbuild(current($arr));
 				$arr_desc[$package] = $e->description;

				$div_versions .= keywordsRow($arr, 'category');
 			}
		}

		foreach(array_keys($arr_packages) as $package) {

 			$name = $arr_name[$package];
 			$desc = $arr_desc[$package];

 			$div_names .= "<tr>\n";
 			$div_names .= "<td class='use' valign='top' style='white-space: nowrap;'><a href='$name/' style='color: black;'>$name</a></td>\n";
 			$div_names .= "<td class='use' valign='top'>$desc</td>\n";
 			$div_names .= "</tr>\n";

		}

		echo "<div id='names' style='display: none;'>\n";
		echo "<table class='content centerpage'>\n";
		echo $div_names;
		echo "</table>\n";
		echo "</div>\n";

		echo "<div id='versions'>\n";
		echo $div_versions;
		echo "</div>\n";

	}

?>
