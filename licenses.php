<?php

	require_once 'inc.header1.php';
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';

	require_once 'class.db.category.php';


	$sql = "SELECT name FROM license ORDER BY name;";
	$arr = $db->getCol($sql);

	echo "<h4>".gettext("SOFTWARE LICENSES")."</h4>\n";

	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";

	echo "<table class='licenses' cellpadding='4' cellspacing='0'>\n";

	$x = 0;

	foreach($arr as $name) {

		$class = getRowClass($x++);

		$url = $base_uri."licenses/$name";

		echo "\t<tr class='$class'>\n";
		echo "\t\t<td><a href='$url'>$name</a></td>\n";
		echo "\t</tr>\n";

	}

	echo "</table>\n";

	require_once 'inc.content2.php';

?>
