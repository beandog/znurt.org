<?
	require_once 'inc.header1.php';
	require_once 'inc.header2.php';
	require_once 'inc.header3.php';
	require_once 'inc.content1.php';
	
	$str = gettext("LINGUAS");
	echo "<h4>$str</h4>\n";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<div class='about'>\n";
	
	echo "<p>Znurt is available to view in a few languages with localized links where available.  Translations are provided by volunteers.  We use <a href='http://www.gnu.org/software/gettext/gettext.html'>gettext</a> to translate the strings on the site from the original, and it works rather well.</p>\n";
	
	echo "<p>If you're interested in creating a version of the site in your native tounge, translations are welcome, as long as you can maintain the file when there are updates needed.  You can request a copy of the latest messages template by contacting beandog at gentoo.org.</p>\n";
	
	$arr = array(
		'Czech' => array('http://cz.znurt.org/', 'cz'),
		'English' => array('http://en.znurt.org/', 'us'),
		'French' => array('http://fr.znurt.org/', 'fr'),
		'German' => array('http://de.znurt.org/', 'de'),
		'Italian' => array('http://it.znurt.org/', 'it'),
		'Russian' => array('http://ru.znurt.org/', 'ru'),
		'Spanish' => array('http://es.znurt.org/', 'es'),
		'Turkish' => array('http://tr.znurt.org/', 'tr',),
	);
	
	echo "<ul>\n";
	
	foreach($arr as $lingua => $arr) {
		echo "<li> <a href='".$arr[0]."'><img src='".$base_uri.'img/flags/'.$arr[1].".png'></a> &nbsp; <a href='".$arr[0]."'>$lingua</a></li>\n";
	}
	
	echo "</ul>\n";
	
	echo "</div>\n";
	
	require_once 'inc.content2.php';
?>
