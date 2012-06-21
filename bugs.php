<?
	$str = gettext("BUGS");
	echo "<h4>$str</h4>";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<div class='about'>\n";
	echo "<p>First bug is, set up a decent bug tracker.</p>\n";
	echo "<p>In the meantime, here's some known bugs:</p>\n";
	echo "<ul>\n";
	echo "<li> package mask check is too aggressive, overlaps on entries with same package</li>\n";
	echo "<li> import &lt;pkg&gt; reference from metadata for use flag descriptions</li>\n";
	echo "</ul>\n";
	
	echo "</div>\n";
		

?>