<?
	require_once 'inc.header1.php';
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';
	
	require_once 'class.db.category.php';
	
	$sql = "SELECT c.name, cd.description FROM category c LEFT OUTER JOIN category_description cd ON cd.category = c.id AND cd.lingua = ".$db->quote($lingua)." ORDER BY c.name;";
	$arr = $db->getAssoc($sql);
	
	$str = gettext('CATEGORIES');
	echo "<h4>$str</h4>\n";
	
	echo "<div class='about'>\n";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<table style='padding-bottom: 25px;' cellpadding='4' cellspacing='0'>\n";
	
	$x = 0;
	
	foreach($arr as $category_name => $description) {
	
		$class = getRowClass($x++);
	
		echo "\t<tr class='$class'>\n";
		echo "\t\t<td valign='top'><a href='$base_uri$category_name'>$category_name</a></td>\n";
		echo "\t\t<td>$description</td>\n";
		echo "\t</tr>\n";
	
	}
	
	echo "</table>\n";
	
	echo "</div>\n";

	require_once 'inc.content2.php';
?>