<?

	require_once 'inc.header1.php';

	$str = gettext("ABOUT");
	echo "<h4>$str</h4>";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<div class='about'>\n";
	
	echo "<p>".gettext("\"the fresh ebuilds\" is a site to track information about packages, ebuilds and their relative metadata supplied by <a href='http://www.gentoo.org/'>Gentoo Linux</a>.")."</p>\n";
	echo "<p>".gettext("This site is not an official Gentoo website.")."</p>\n";
	echo "<p>".gettext("Original Gentoo artwork and logos copyright &copy; Gentoo Foundation.")."</p>\n";
	echo "<p>".sprintf(gettext("\"the fresh ebuilds\" original design and artwork by %s."), "<a href='http://www.molanphydesign.com/'>Molanphy Design</a>")."</p>\n";
	echo "<p>".sprintf(gettext("Icon set copyright &copy; %s."), "<a href='http://www.famfamfam.com/'>Mark James</a>")."</p>\n";
	echo "<p>".sprintf(gettext("Original code and site maintenance by %s."), "<a href='http://wonkabar.org/'>Steve Dibb</a>")."</p>\n";
	echo "<p>".sprintf(gettext("Powered by %s and lots of chocolate-chip cookies."), "<a href='http://www.gentoo.org/'>Gentoo Linux</a>, <a href='http://httpd.apache.org/'>Apache 2</a>, <a href='http://www.php.net/'>PHP 5</a>, <a href='http://www.postgresql.org/'>PostgreSQL 8</a>")."</p>\n";
	
	if($locale == "de_DE") {
		echo "<p>Übersetzung durch <a href='http://dev.gentoo.org/~patrick/'>Patrick Lauer</a> und <a href='http://gentoo.faulhammer.org/'>Christian Faulhammer</a>.\n";
	} elseif($locale == "el_GR") {
		echo "<p>Η ελληνική μετάφραση έγινε από τον Νικόλαο Χατζηδάκη\n";
	}
		
	echo "</div>\n";
		
?>
