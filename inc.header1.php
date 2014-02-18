<?

	require_once 'inc.gentoo.php';
	require_once 'cache.start.php';
 	require_once 'inc.i18n.php';
	require_once 'class.db.ebuild.php';
	require_once 'class.db.package.php';
	require_once 'class.portage.package.changelog.php';
	
	$html_title = gettext("the fresh ebuilds");
	
  	$sql = "SELECT name FROM arch WHERE active IS TRUE ORDER BY name;";
  	$arr_arch = $db->getCol($sql);
  	
  	$arr_default_arch = $arr_display_arch = array('alpha', 'amd64', 'arm', 'hppa', 'ia64', 'm68k', 'mips', 'ppc', 'ppc64', 's390', 'sh', 'sparc', 'x86');
  	
  	$arr = array();
  	if($_COOKIE['arch']) {
  		foreach($_COOKIE['arch'] as $name => $value) {
  			if($name && in_array($name, $arr_arch, true) && $value)
  				$arr[] = $name;
  		}
  	}
  	
 	if(count($arr)) {
 		sort($arr);
 		$arr_display_arch = $arr;
 	}
 	
 	$offset = 0;
 	$amount = 10;
	
	$request_uri = $_SERVER['REQUEST_URI'];
	
	function headerMessage($str) {
		$str = "<table class='centerpage'><tr><th class='category'>$str</th></tr></table>\n";
		return $str;
	}
	
	function recentPackages($amount, $offset = 0, $arch = "") {
	
		$db =& MDB2::singleton();
		
		if($arch) {
// 			$sql ="SELECT DISTINCT pr.package FROM package_recent pr INNER JOIN ebuild e ON e.package = pr.package INNER JOIN ebuild_arch ea ON ea.ebuild = e.id AND ea.arch != 2 INNER JOIN arch a ON ea.arch = a.id AND a.name = ".$db->quote($arch)." ORDER BY max_ebuild_mtime DESC, package LIMIT $amount OFFSET $offset";
			$sql = "SELECT package FROM package_recent_arch pra INNER JOIN arch a ON pra.arch = a.id AND a.name = ".$db->quote($arch)." WHERE pra.status = 0 ORDER BY pra.max_ebuild_mtime DESC, pra.package LIMIT $amount OFFSET $offset";
		} else {
			$sql = "SELECT package FROM package_recent WHERE status = 0 ORDER BY max_ebuild_mtime DESC, package LIMIT $amount OFFSET $offset";
		}
	
		// Note to sanity: I already found one instance (readline version bump to 6.1 ebuild) where the recent changes
		// said "version bump" but the most recent ebuild was 5.2_p14.  Everything was working fine, because the 5.2 ebuild's
		// mtime really was *newer*.  So, just because the recent changes doesn't match up to the display, don't panic.
		
		// This tracks what was the *last modified* ebuild *PLUS* an hour before that, to get (ostensibly) all the changes
		// since the last run.
 		$sql = "SELECT e.package, e.id AS ebuild FROM ebuild e INNER JOIN package_recent pr ON e.package = pr.package AND e.cache_mtime > (pr.max_ebuild_mtime - 3600) WHERE pr.status = 0 AND e.status = 0 AND e.package IN ($sql) ORDER BY pr.max_ebuild_mtime DESC, e.package, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC;";
 		
//   		echo $sql;
 		
  		$arr = $db->getAll($sql);
 		
		return $arr;
		
	}
	
	function keywordsRow($arr, $view = 'category') {
	
		global $arr_arch;
		
		global $arr_display_arch;
		
		global $base_uri;
		
		global $base_url;
		
		global $lingua;
		
		$request_uri = $_SERVER['REQUEST_URI'];
		if(substr($request_uri, -1) == '/')
			$request_uri = substr($request_uri, 0, strlen($request_uri) - 1);
	
		$e = new DBEbuild(current($arr));
		$p = new DBPackage($e->package);
		$c = new PackageChangelog($p->changelog);
		
		$recent_changes = htmlspecialchars($p->recent_changes);
		$recent_changes = preg_replace('/((bug\n?)( {1,2}\D?)|#)(\d{5,})/i', "<a href='https://bugs.gentoo.org/$4'>$1$4</a>", $recent_changes);
		$recent_date = $c->recent_date;
		
		$category_name = $e->category_name;
		$package_name = $e->package_name;
		$cp = "$category_name/$package_name";
	
		$description = htmlspecialchars($p->description);
		
		$iarr_months = array(
			'January' => gettext('January'),
			'February' => gettext('February'),
			'March' => gettext('March'),
			'April' => gettext('April'),
			'May' => gettext('May'),
			'June' => gettext('June'),
			'July' => gettext('July'),
			'August' => gettext('August'),
			'September' => gettext('September'),
			'October' => gettext('October'),
			'November' => gettext('November'),
			'December' => gettext('December'),
		);
		
		// Portage mtimes on directories are generally unreliable
		// directories get their mtime updated for no reasons that I can see.
		$max = max($e->cache_mtime, $e->changelog_mtime, $e->metadata_mtime, $e->portage_mtime);
		if($lingua == "es")
			$mdate = date('d', $max)." de ".gettext(strtolower(date('F', $max)))." ".date(', Y, H:i', $max);
		elseif($lingua == "de" || $lingua == "cs")
			$mdate = date('d.')." ".gettext(date('F', $max))." ".date('Y, H:i', $max);
		else
			$mdate = date('F d, Y, H:i', $max);
		
		$homepage = ( $e->homepage ? $e->homepage : "http://www.gentoo.org/" );
		
		$url_category = urlencode($category_name);
		$url_package = urlencode($package_name);
		$url_pf = urlencode($e->pf);
		
 		$gentoo_changelog = "http://sources.gentoo.org/viewcvs.py/*checkout*/gentoo-x86/$url_category/$url_package/ChangeLog";
		$gentoo_cvs = "http://sources.gentoo.org/viewcvs.py/gentoo-x86/$url_category/$url_package/?hideattic=0";
		$gentoo_bugs = "https://bugs.gentoo.org/buglist.cgi?query_format=&amp;short_desc_type=allwords&amp;short_desc=$url_package&amp;bug_status=UNCONFIRMED&amp;bug_status=NEW&amp;bug_status=ASSIGNED&amp;bug_status=REOPENED";
		$gentoo_wiki = "http://wiki.gentoo.org/index.php?title=Special%3ASearch&amp;search=".urlencode(str_replace("-", " ", $package_name));
		$gentoo_forums = "http://forums.gentoo.org/search.php?search_terms=all&amp;show_results=topics&amp;search_keywords=$url_package&amp;mode=results";
		
		$bugs = $base_uri."$url_category/$url_package/bugs";
		$changelog = $base_uri."$url_category/$url_package/changelog";
		$ml = "http://www.mail-archive.com/search?q=$url_package&amp;l=gentoo-user%40lists.gentoo.org";
		
		if($lingua == "cs")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.czech-slovak";
		elseif($lingua == "de")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.german";
		elseif($lingua == "fr")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.french";
		elseif($lingua == "hu")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.hungarian";
		elseif($lingua == "id")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.indonesia";
		elseif($lingua == "ru")
			$ml = "http://search.gmane.org/?query=$url_package&amp;group=gmane.linux.gentoo.user.russian";
		
		if($view == 'ebuild') {
			$dependencies = $base_uri."$url_category/$url_pf/dependencies";
			$downloads = $base_uri."$url_category/$url_pf/downloads";
			$license = $base_uri."$url_category/$url_pf/license";
			$source = $base_uri."$url_category/$url_pf/source";
			$useflags = $base_uri."$url_category/$url_pf/useflags";
		} else {
			$dependencies = $base_uri."$url_category/$url_package/dependencies";
			$downloads = $base_uri."$url_category/$url_package/downloads";
			$license = $base_uri."$url_category/$url_package/license";
			$useflags = $base_uri."$url_category/$url_package/useflags";
		}
		
		// License
// 		$arr_licenses = $e->licenses;
// 		
// 		$url_licenses = array();
// 		
// 		if(count($arr_licenses)) {
// 			foreach($arr_licenses as $name) {
// 				$str = nl2br(wordwrap(str_replace('-', ' ', $name), 25));
// 				$url_licenses[$str] = $base_uri."licenses/".urlencode($name);
// 			}
// 		}
		
		$x = 0;
		
		/** New **/
// 		$html .= "<!-- start package -->\n";
		$html .= "<div class='package'>\n";
		$html .= "\t<hr class='pkg_rule'>\n";
		$html .= "\t\t<div class='pkg_container'>\n";
		
		$html .= "\t\t\t<div class='pkg_name'><a class='pkg_link' href='$base_uri$url_category/$url_package' title='$cp'>$package_name</a></div>\n";
	
		// FIXME CSS bug
// 		if($view == 'category')
// 			$str = "&nbsp;";
// 		else
			$str = $mdate;
		
		$html .= "\t\t\t\t<div class='pkg_date'>$str</div>\n";
		
 		if($view == 'ebuild' || $view == 'new' || $view == 'category' || $view == 'search')
 			$html .= "\t\t\t\t<p class='description' style='padding-top: 25px;'>$description</p>\n";
		
		$html .= "\t\t\t\t\t<table class='releases' cellspacing='0' cellpadding='0'>\n";
		$html .= "\t\t\t\t\t\t<tr>\n";
		$html .= "\t\t\t\t\t\t\t<td><b></b></td>\n";
		
		foreach($arr_display_arch as $name) {
			$class = "nowrap";
			if($name == end($arr_display_arch))
				$class = "$class last_cell";
			
			// FIXME CSS in style tag
			$html .= "\t\t\t<th class='$class'>$name</th>\n";
		}
		
		$html .= "\t\t\t\t\t\t</tr>\n";
		
		$istr_bugs = gettext("Bugs");
		$istr_category = gettext("Category");
		$istr_changelog = gettext("ChangeLog");
		$istr_cvs = gettext("CVS");
		$istr_dependencies = gettext("Dependencies");
		$istr_forums = gettext("Forums");
		$istr_homepage = gettext("Homepage");
		$istr_license = gettext("License");
		$istr_mailing_lists = gettext("Mailing Lists");
		$istr_metadata = gettext("Metadata");
		$istr_package_description = gettext("PACKAGE DESCRIPTION");
		$istr_planet = gettext("Planet");
		$istr_recent_changes = gettext("Recent Changes");
		$istr_source = gettext("Source");
		$istr_use_flags = gettext("Use Flags");
		$istr_wiki = gettext("Wiki");
		
		foreach($arr as $ebuild) {
	
			if($x > 0)
				$e = new DBEbuild($ebuild);
			
			$x++;
			
			$arr_keywords = $e->keywords;
			
			$pf =& $e->pf;
			$pv =& $e->pv;
			$pvr =& $e->pvr;
			
			$url_pf = urlencode($pf);
			
			$url = $base_uri.$url_category."/".$url_package."/$pf";
			
			if($view == 'ebuild')
				$gentoo_cvs = "http://sources.gentoo.org/viewcvs.py/*checkout*/gentoo-x86/$url_category/$url_package/$url_pf.ebuild";
			
			$html .= "\t\t\t\t\t\t<tr>\n";
			$html .= "\t\t\t\t\t\t\t<td class='first_cell'><a href='$url' title='$pf'>$pvr</a></td>\n";
			
			foreach($arr_display_arch as $arch) {
			
			
					$case = $arr_keywords[$arch];
				
					if($arch == 'sparc fbsd')
						$arch = 'sparc-fbsd';
					elseif($arch == 'x86 fbsd')
						$arch = 'x86-fbsd';
				
					switch($case) {
					
						case null:
							$class = 'not_keyword';
							$keyword = '&ndash;';
							break;
					
						case 0:
							$class = 'stable';
							$keyword = '+';
							break;
						
						case 1:
							$class = 'testing';
							$keyword = '~';
							break;
						
						// FIXME
						case 2:
						case 3:
							$class = 'not_avail';
							$keyword = 'x';
							break;
						
						default:
							$class = 'not_keyword';
							$keyword = '-';
							break;
					
					}
					
				if($e->masked && ($case == 0 || $case == 1)) {
					$class = 'm_stable';
					$keyword = 'm';
				}
				
				
				if($arch == end($arr_display_arch))
					$class .= " last_cell";
				
				$html .= "<td class='$class'>$keyword</td>\n";
			
			}
			
			
			$html .= "\t\t\t\t\t\t</tr>\n";
			
		}
		
		$html .= "\t\t\t\t\t</table>\n";
		
// 		if($view == 'ebuild' || $view == 'new' || $view == 'category')
//  			$html .= "\t\t\t\t<p class='recent_changes'><b>Description:</b> $description</p>\n";
		
		if($recent_changes && ($view == 'new' || $view == 'package'))
			$html .= "\t\t\t\t<p class='recent_changes'><b>$istr_recent_changes:</b> &nbsp; $recent_changes</p>\n";
			
		$html .= "\t\t\t</div>\n";
		
		if($view == 'new' ||  $view == 'category' || $view == 'search') {
		
			$html .= "\t\t\t\t<div class='pkg_row'>\n";
			
			$html .= "\t\t\t\t\t<ul>\n";
			
			$html .= "\t\t\t\t\t\t<li class='meta_homepage'><a href='$homepage' rel='nofollow'>$istr_homepage</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_forums'><a href='$gentoo_forums'>$istr_forums</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_mailing_list'><a href='$ml'>$istr_mailing_lists</a></li>\n";
			if($lingua != "cs")
				$html .= "\t\t\t\t\t\t<li class='meta_wiki'><a href='$gentoo_wiki'>$istr_wiki</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_changelog'><a href='$changelog' onclick=\"$('data').update($('changelog').innerHTML); return false;\">$istr_changelog</a></li>\n";
 			$html .= "\t\t\t\t\t\t<li class='meta_useflags'><a href='$useflags'>$istr_use_flags</a></li>\n";
 			if($view == 'new' || $view == 'search') {
 				$html .= "\t\t\t\t\t\t<li class='meta_package'><a href='$base_uri$url_category'>$category_name</a></li>\n";
 			} else {
 				$html .= "\t\t\t\t\t\t<li class='meta_bugs'><a href='$bugs' onclick=\"$('data').update($('bugs').innerHTML); return false;\">$istr_bugs</a></li>\n";
 				$html .= "\t\t\t\t\t\t<li class='meta_database_table'><a href='$gentoo_cvs'>$istr_cvs</a></li>\n";
 			}
			
			$html .= "\t\t\t\t\t</ul>\n";
			$html .= "\t\t\t\t</div>\n";
		
		}
		
		if($view == 'package' || $view == 'ebuild') {
			
			$html .= "\t\t\t<div class='pkg_meta'>\n";
			
			$html .= "\t\t\t\t<div class='pkg_desc'>\n";
			$html .= "\t\t\t\t\t<h4>$istr_package_description:</h4>\n";
			$html .= "\t\t\t\t\t<p>$description</p>\n";
			$html .= "\t\t\t\t</div>\n";
			
			$html .= "\t\t\t\t<div class='vr_dotted'></div>\n";
			
			$html .= "\t\t\t\t<div class='pkg_col'>\n";
			$html .= "\t\t\t\t\t<h4>$istr_category:</h4>\n";
			$html .= "\t\t\t\t\t<p><a href='".$base_uri.$url_category."'>$category_name</a></p>\n";
			$html .= "\t\t\t\t</div>\n";
			
// 			$html .= "\t\t\t\t<div class='vr_dotted'></div>\n";
			
// 			$html .= "\t\t\t\t<div class='pkg_col'>\n";
// 			$html .= "\t\t\t\t\t<ul>\n";
// 			$html .= "\t\t\t\t\t<h4>$istr_license:</h4>\n";
// 			foreach($url_licenses as $name => $url)
// 				$html .= "\t\t\t\t\t\t<li><a href='$url'>$name</a></li>\n";
// 			$html .= "\t\t\t\t\t</ul>\n";
// 			$html .= "\t\t\t\t</div>\n";
			
			$html .= "\t\t\t\t<div class='vr_dotted'></div>\n";
			
			$html .= "\t\t\t\t<div class='pkg_col'>\n";
			$html .= "\t\t\t\t\t<ul>\n";
			
			$html .= "\t\t\t\t\t\t<li class='meta_homepage'><a href='$homepage' rel='nofollow' target='_blank'>$istr_homepage</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_changelog'><a href='$changelog' onclick=\"$('data').update($('changelog').innerHTML); return false;\">$istr_changelog</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_bugs'><a href='$bugs' onclick=\"$('data').update($('bugs').innerHTML); return false;\">$istr_bugs</a></li>\n";
			
			$html .= "\t\t\t\t\t</ul>\n";
			$html .= "\t\t\t\t</div>\n";
			
			$html .= "\t\t\t\t<div class='vr_dotted'></div>\n";
			
			$html .= "\t\t\t\t<div class='pkg_col'>\n";
			$html .= "\t\t\t\t\t<ul>\n";
			
			$html .= "\t\t\t\t\t\t<li class='meta_forums'><a href='$gentoo_forums' target='_blank'>$istr_forums</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_mailing_list'><a href='$ml' target='_blank'>$istr_mailing_lists</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_wiki'><a href='$gentoo_wiki' target='_blank'>$istr_wiki</a></li>\n";
	 		// $html .= "\t\t\t\t\t\t<li class='meta_dependencies'><a href='$dependencies' onclick=\"$('data').update($('dependencies').innerHTML); return false;\">$istr_dependencies</a></li>\n";
			
			$html .= "\t\t\t\t\t</ul>\n";
			$html .= "\t\t\t\t</div>\n";
			
			$html .= "\t\t\t\t<div class='vr_dotted'></div>\n";
			
			$html .= "\t\t\t\t<div class='pkg_col'>\n";
			$html .= "\t\t\t\t\t<ul>\n";
			
// 			$html .= "\t\t\t\t\t\t<li class='meta_downloads'><a href='$downloads' onclick=\"$('data').update($('downloads').innerHTML); return false;\">Downloads</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_useflags'><a href='$useflags' onclick=\"$('data').update($('useflags').innerHTML); return false;\">$istr_use_flags</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_license'><a href='$license' onclick=\"$('data').update($('license').innerHTML); return false;\">License</a></li>\n";
			$html .= "\t\t\t\t\t\t<li class='meta_cvs'><a href='$gentoo_cvs' target='_blank'>View CVS</a></li>\n";
			if($view == 'ebuild')
				$html .= "\t\t\t\t\t\t<li class='meta_source'><a href='$source' onclick=\"$('data').update($('source').innerHTML); return false;\">Source Code</a></li>\n";
				
			
			$html .= "\t\t\t\t\t</ul>\n";
			$html .= "\t\t\t\t</div>\n";
			
			$html .= "\t\t\t\t<div class='clear'></div>\n";
			
			$html .= "\t\t\t</div>\n";
		}
		
		$html .= "\t\t</div>\n";
		
		return $html;
	
	}
	
	function getRowClass($i = 0) {
		$i = intval($i);
		
		if(($i % 2) == 0)
			return 'odd';
		else
			return 'even';
	}
	
?>
