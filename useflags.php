<?
	require_once 'inc.header1.php';
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';
	
	require_once 'class.db.category.php';
	
	$sql = "SELECT name, description FROM use WHERE LENGTH(description) > 0 AND prefix = '' ORDER BY name;";
	$arr = $db->getAssoc($sql);
	
	echo "<h4>".gettext("use flags")."</h4>\n";
	
	echo "<div class='about'>\n";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<table style='padding-bottom: 25px;' cellpadding='4' cellspacing='0'>\n";
	
	$x = 0;
	
	foreach($arr as $useflag_name => $description) {
	
		$class = getRowClass($x++);
		
		$url = $base_uri."useflags/".urlencode($useflag_name);
	
		echo "\t<tr class='$class'>\n";
		echo "\t\t<td valign='top'><a href='$url'>$useflag_name </a></td>\n";
		echo "\t\t<td>$description</td>\n";
		echo "\t</tr>\n";
	
	}
	
	echo "</table>\n";
	
	echo "</div>\n";
	
	require_once 'inc.content2.php';
?>