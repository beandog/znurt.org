<?
	
	if($license_name) {
	
		$str = gettext("LICENSE:");
	
		if($lingua == "de")
			echo "<h4>Nutzungsbedingung: $license_name</h4>";
		else
			echo "<h4>$license_name license</h4>";
		
		echo "<div class='description'>$description</div>";

		$sql = "SELECT category_name, package_name, description FROM view_package_licenses WHERE license_name = ".$db->quote($license_name)." ORDER BY category_name, package_name;";
		$arr = $db->getAll($sql);
		
		echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
		echo "<table class='licenses' cellpadding='4' cellspacing='0'>\n";
		
		$x = 0;
		
		foreach($arr as $row) {
		
			extract($row);
		
			$class = getRowClass($x++);
			
			$cp = "$category_name/$package_name";
			$url = $base_uri.$cp;
		
			echo "\t<tr class='$class'>\n";
			echo "\t\t<td valign='top'><a href='$url'>$cp</a></td>\n";
			echo "\t\t<td>$description</td>\n";
			echo "\t</tr>\n";
		
		}
		
		echo "</table>\n";
		
	}

?>