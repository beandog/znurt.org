<?php

	$arr_chunk = array_chunk($arr_arch, round(count($arr_arch) / 2));

	$url = $base_uri."xml.php";

	echo "<h4>".gettext("Subscription Feeds")."</h4>";

	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";

	echo "<h4>".gettext("fresh ebuilds")."</h4>";

	echo "<div class='about'>\n";

	$dir = $base_uri."xml/fresh_ebuilds/";
	$atom = $dir."atom.xml";
	$rss = $dir."rss.xml";

	echo "<p><table cellpadding='4' cellspacing='0' style='width: 50%;'>\n";

	$x = 1;

	$atom = $base_url."xml/feeds/fresh_ebuilds/atom.xml";
	$rss = $base_url."xml/feeds/fresh_ebuilds/rss.xml";

	echo "<tr class='odd'><td>".gettext("All Architectures")."</td><td><a href='$atom'>Atom</a></td><td><a href='$rss'>RSS</a></td></tr>\n";

	foreach($arr_arch as $name) {

		$url_name = urlencode($name);

		$class = getRowClass($x++);

		$atom = $base_url."xml/feeds/fresh_ebuilds/atom.$url_name.xml";
		$rss = $base_url."xml/feeds/fresh_ebuilds/rss.$url_name.xml";

		echo "<tr class='$class'><td style='width: 50%;'>$name</td><td style='width: 25%;'><a href='$atom'>Atom</a></td><td style='width: 25%;'><a href='$rss'>RSS</a></td></tr>\n";

	}

	echo "</table>\n";

	echo "</div>\n";

	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";

	echo "<h4>".gettext("new packages")."</h4>";

	echo "<div class='about'>\n";

	$atom = $base_url."xml/feeds/new_packages/atom.xml";
	$rss = $base_url."xml/feeds/new_packages/rss.xml";

	echo "<table cellpadding='4' cellspacing='0' style='width: 50%; padding-bottom: 25px;'>\n";
	echo "\t<tr class='odd'>\n";
	echo "<td style='width: 50%;'>".gettext("All Architectures")."</td><td style='width: 25%;'><a href='$atom'>Atom</a></td><td style='width: 25%;'><a href='$rss'>RSS</a></td>\n";
	echo "\t</tr>\n";

	$x = 1;

	echo "</table>\n";

	echo "</div>\n";

?>
