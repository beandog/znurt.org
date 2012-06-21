<?

	$nocache = true;

	require_once 'inc.header1.php';
	require_once 'class.db.package.php';
	require_once 'class.db.package.changelog.php';
	require_once 'class.db.category.php';
	require_once 'class.portage.package.changelog.php';
	
	// Web interface
	// meh
	$atom = true;
	if($_GET['type'] == 'rss') {
		$atom = false;
	}
	
	$arch = $_GET['arch'];
	if(!in_array($arch, $arr_arch))
		$arch = null;
		
	$feed = $_GET['feed'];
		
	// CLI interface
	if(count($argv)) {
		$cli = true;
		$feed = $argv[1];
		$type = $argv[2];
		if($argv[3] && in_array($argv[3], $arr_arch))
			$arch = $argv[3];
		
		if($type == 'rss')
			$atom = false;
	}
	
	$feed_amount = 100;
	
	switch($feed) {
	
		case 'new_packages':
			$view = 'new_packages';
// 			$sql = "SELECT p.id AS package, c.name AS category_name, p.name AS package_name, p.idate FROM category c JOIN package p ON p.category = c.id WHERE p.portage_mtime IS NOT NULL ORDER BY p.idate DESC, category_name, package_name LIMIT $feed_amount;";
   
//    			$sql = "SELECT p.id AS package, e.category_name, p.name AS package_name, e.id AS ebuild, p.idate FROM package p LEFT OUTER JOIN ebuilds e ON e.package = p.id WHERE p.portage_mtime IS NOT NULL AND e.id IS NOT NULL ORDER BY p.idate DESC, e.cache_mtime DESC, e.category_name, e.package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC LIMIT $feed_amount;";
   			$sql = "SELECT p.id AS package, e.id AS ebuild FROM package p LEFT OUTER JOIN ebuilds e ON e.package = p.id WHERE p.idate > '2010-01-04 12:00:00.0-07' AND p.portage_mtime IS NOT NULL AND e.id IS NOT NULL ORDER BY p.idate DESC, e.cache_mtime DESC, e.category_name, e.package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC LIMIT $feed_amount;";
   			
			$arr = $db->getAll($sql);
			$feed_title = "new packages";
			
			if($atom)
				$self_url = "http://znurt.org/xml/feeds/new_packages/atom.xml";
			else
				$self_url = "http://znurt.org/xml/feeds/new_packages/rss.xml";
			
			break;
			
		case 'new_ebuilds':
		
			$sql = "SELECT e.package, e.id AS ebuild FROM package p LEFT OUTER JOIN ebuilds e ON e.package = p.id WHERE e.udate IS NULL AND e.idate > '2010-01-04 12:00:00.0-07' ORDER BY e.idate DESC, e.cache_mtime DESC, e.category_name, e.package_name, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC;";
			
			$arr = $db->getAll($sql);
			$feed_title = "new ebuilds";
			
			if($atom)
				$self_url = "http://znurt.org/xml/feeds/new_ebuilds/atom.xml";
			else
				$self_url = "http://znurt.org/xml/feeds/new_ebuilds/rss.xml";
			
			break;
	
		default:
			$view = 'fresh_ebuilds';
			$feed_title = "the fresh ebuilds";
			$arr = recentPackages($feed_amount, 0, $arch);
			
			$str = "";
			if($arch) {
				$str = "$arch.";
				$feed_title .= " ~ $arch";
			}
			
			if($atom)
				$self_url = "http://znurt.org/xml/feeds/fresh_ebuilds/atom.".$str."xml";
			else
				$self_url = "http://znurt.org/xml/feeds/fresh_ebuilds/rss.".$str."xml";
			
			break;
		
	}
	
	if($atom)
		$date_format = 'c';
	else
		$date_format = 'D, d M Y H:i:s O';
	
//     	Common::pre($arr);
	
	
	$feed_updated = $feed_published = date($date_format);
	$feed_time = time();
	
	$entries = array();
	
	if(count($arr)) {
	
		$e = new DBEbuild($arr[0]['ebuild']);
	
		$feed_updated = date($date_format, $e->portage_mtime);
		
		$x = 0;
		
		foreach($arr as $row) {
		
			extract($row);
			
			$e = new DBEbuild($ebuild);
			$p = new DBPackage($package);
			$c = new DBCategory($p->category);
			$ch = new DBPackageChangelog($package);
			
			$package_name = $p->name;
			$category_name = $c->name;
			$pf = $e->pf;
			$ebuild_portage_mtime = $e->portage_mtime;
			$recent_changes = htmlspecialchars($ch->recent_changes);
			$description = htmlspecialchars($p->description);
				
			$entries[$x] = $row;
			
			if($view == 'new_packages') {
				$entries[$x]['title'] = "$category_name/$package_name";
				$entries[$x]['portage_mtime'] = strtotime($p->portage_mtime);
			} else {
				$entries[$x]['title'] = "$pf";
			}
			
			$entries[$x]['package_url'] = "http://znurt.org/$category_name/$package_name";
			$entries[$x]['ebuild_url'] = "http://znurt.org/$category_name/$package_name/$pf";
			$entries[$x]['updated'] = date($date_format, $ebuild_portage_mtime);
			
			$entries[$x]['id'] = $entries[$x]['ebuild_url']."#$ebuild_portage_mtime";
			
			$entries[$x]['cp'] = htmlspecialchars("$category_name/$package_name");
			$entries[$x]['recent_changes'] = $recent_changes;
			$entries[$x]['description'] = $description;
		
			$x++;
		
		}
		
	}
	
	
	if($atom) {
	
		header('Content-type: application/atom+xml');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	
		echo "<feed xmlns='http://www.w3.org/2005/Atom'>\n";
		echo "\t<link rel='self' href='$self_url' />\n";
		echo "\t<title>$feed_title</title>\n";
		echo "\t<updated>$feed_updated</updated>\n";
		echo "\t<id>$self_url#$feed_time</id>\n";
		echo "\t<author>\n";
		echo "\t\t<name>Steve Dibb</name>\n";
		echo "\t\t<email>beandog@gentoo.org</email>\n";
		echo "\t</author>\n";
		
		if(count($entries)) {
		
			foreach($entries as $row) {
				
				extract($row);
				
				echo "\t<entry>\n";
				echo "\t\t<title>$title</title>\n";
				echo "\t\t<link rel='alternate' type='text/html' href='$package_url' />\n";
				echo "\t\t<id>$id</id>\n";
 				echo "\t\t<updated>$feed_updated</updated>\n";
				echo "\t\t<published>$updated</published>\n";
				echo "\t\t<content type='xhtml' xml:lang='en' xml:base='http://znurt.org/'>\n";
				echo "\t\t\t<div xmlns='http://www.w3.org/1999/xhtml'>\n";
				echo "\t\t\t\t<p><b>$cp</b> - $description</p>\n";
				echo "\t\t\t\t<p>$recent_changes</p>\n";
				echo "\t\t\t</div>\n";
				echo "\t\t</content>\n";
				echo "\t</entry>\n";
			
			}
		
		}
	
		echo "</feed>\n";
	} else {
	
		header('Content-type: application/rss+xml');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		
		echo "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n";
	
		echo "<channel>\n";
		echo "\t<atom:link href='$self_url' rel='self' type='application/rss+xml' />\n";
		echo "\t<title>$feed_title</title>\n";
		echo "\t<link>http://znurt.org/</link>\n";
		echo "\t<description>the fresh ebuilds</description>\n";
		echo "\t<language>en-us</language>\n";
		echo "\t<lastBuildDate>$feed_updated</lastBuildDate>\n";
		echo "\t<pubDate>$feed_published</pubDate>\n";
		echo "\t<managingEditor>beandog@gentoo.org (Steve Dibb)</managingEditor>\n";
		echo "\t<webMaster>beandog@gentoo.org (Steve Dibb)</webMaster>\n";
		echo "\t<ttl>60</ttl>\n";
		
		if(count($entries)) {
		
			foreach($entries as $row) {
				
				extract($row);
				
				echo "\t<item>\n";
				echo "\t\t<title>$title</title>\n";
				echo "\t\t<link>$package_url</link>\n";
				echo "\t\t<guid isPermaLink='false'>$id</guid>\n";
 				echo "\t\t<pubDate>$updated</pubDate>\n";
 				echo "\t\t<description>\n";
 				echo "<![CDATA[\n";
 				echo "<p><b>$cp</b> - $description</p>\n";
 				echo "<p>$recent_changes</p>\n";
 				echo "]]>\n";
 				echo "</description>\n";
				echo "\t</item>\n";
			
			}
		
		}
		
		echo "</channel>\n";
		echo "</rss>\n";
		
	}
	
	require_once 'inc.footer.php';
	
?>