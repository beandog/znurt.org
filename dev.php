<?php

	require_once 'inc.header1.php';
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';

	$str = gettext("DEVELOPMENT");
	echo "<h4>$str</h4>\n";

	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";

	echo "<div class='about'>\n";

	echo "<ul>\n";

	echo "<li> <a href='https://github.com/beandog/znurt.org/issues'>znurt.org frontend bugtracker</a></li>\n";
	echo "<li> <a href='https://github.com/beandog/znurt.org'>znurt.org frontend git</a></li>\n";
	echo "<li> <a href='https://github.com/beandog/znurt'>znurt.org backend git</a></li>\n";
	echo "<li> <a href='/xml'>XML</a></li>\n";

	echo "</div>\n";

	require_once 'inc.content2.php';

?>
