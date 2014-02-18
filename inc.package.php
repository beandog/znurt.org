<?
		
		$p = new DBPackage($package_id);
		
		$str_changelog = $p->changelog;
		
		echo "<div id='data'></div>";
		
		/** Changelog **/
		echo "<div id='changelog' style='display: none;'>\n";
		
		$arr_patterns = array(
			'/((bug\n?)( {1,2}\D?)|#)(\d+)/i',
 			'/([0123]?[0-9] (Jan|Feb|Mar|Apr|May|Ju[nl]|Aug|Sep|Oct|Nov|Dec) (19|20)\d{2});/',
  			'/&lt;(.+)@gentoo.org&gt;/'
		);
		$arr_replacements = array(
			"<a href='https://bugs.gentoo.org/$4'>$1$4</a>",
 			"<span class='date'>$1</span>;",
  			"(<span class='dev'>$1</span>)",
		);
		
 		$str_changelog = wordwrap($str_changelog, 80);
		$str_changelog = htmlspecialchars($str_changelog);
		$str_changelog = preg_replace($arr_patterns, $arr_replacements, $str_changelog);
		
		if(!empty($str_changelog)) {
		
			$str = gettext("CHANGELOG");
		
			echo "<h4>$str</h4>";
			
			echo "<div style='margin-left: 25px; margin-bottom: 25px;'>\n";
			echo "<div class='changelog'>";
			echo "<pre>";
			echo $str_changelog;
			echo "</pre>";
			echo "</div>";
			
			echo "</div>\n";
			
		}
		
		echo "</div>\n";
		
		/** Bugs **/
		echo "<div id='bugs' style='display: none;'>\n";
		
		$gentoo_bugs = "https://bugs.gentoo.org/buglist.cgi?quicksearch=".urlencode($p->name);
		
		$str = gettext("BUGS");
		
		echo "<h4>$str</h4>\n";
		
		$sql = "SELECT bug, description FROM package_bugs WHERE package = ".$db->quote($package_id)." AND status = 0 ORDER BY bug;";
		$arr = $db->getAssoc($sql);
		
		if(count($arr)) {
		
			echo "<table class='bugs' cellpadding='4' cellspacing='0'>\n";
// 			echo "\t<tr>\n";
// 			echo "\t\t<th>Bug</th><th>Description</th>\n";
// 			echo "\t</tr>\n";
			
			$x = 0;
		
			foreach($arr as $bug => $description) {
			
				$class = getRowClass($x++);
			
				echo "\t<tr class='$class'>\n";
				echo "\t\t<td><a href='https://bugs.gentoo.org/$bug'>$bug</a></td>\n";
				echo "\t\t<td>".htmlentities($description)."</td>\n";
				echo "\t</tr>\n";
			
			}
			
			echo "</table>\n";
			
		} else {
			$str = gettext("No bugs found");
			echo "<p><b>$str</b></p>\n";
		}
		
		
		if($lingua == "en")
			echo "<p style='margin: 0 20px;'><b>Notes:</b> This list is taken from a snapshot, and is not a reliable reference.  Search <a href='$gentoo_bugs'>bugzilla</a> for accurate results.</p>\n";
		
		echo "</div>\n";
		
		
		/** Use Flags **/
		echo "<div id='useflags' style='display: none;'>\n";
		
		$str = gettext("USE FLAGS");
		echo "<h4>$str</h4>\n";
		
		if($view == 'ebuild')
			$sql = "SELECT name, description FROM view_ebuild_use WHERE ebuild = ".$db->quote($ebuild_id)." ORDER BY name;";
		else
			$sql = "SELECT name, description FROM view_package_use WHERE package = ".$db->quote($package_id)." ORDER BY name;";
		
		$arr = $db->getAssoc($sql);
		
		if(count($arr)) {
			echo "<table class='useflags' cellpadding='4' cellspacing='0'>\n";
		
			$x = 0;
		
			foreach($arr as $name => $description) {
			
				$class = getRowClass($x++);
			
				$url = $base_uri.'useflags/'.urlencode($name);
			
				echo "\t<tr class='$class'>\n";
				echo "\t\t<td valign='top'><a href='$url'>$name</a></td>\n";
				echo "\t\t<td>".htmlentities($description)."</td>\n";
				echo "\t</tr>\n";
			
			}
			
			echo "</table>\n";
		} else {
		
			$str = gettext("No Use Flags");
			echo "<div class='about'>$str</div>";
		}
		
		echo "</div>\n";
		
		/** Dependencies **/
		echo "<div id='dependencies' style='display: none;'>\n";
		
		if($view == 'ebuild')
			$sql = "SELECT type, cp, description FROM view_ebuild_depend WHERE ebuild = ".$db->quote($ebuild_id)." ORDER BY cp;";
		else
			$sql = "SELECT type, cp, description FROM view_package_depend WHERE package = ".$db->quote($package_id)." ORDER BY cp;";
			
		$arr = $db->getAll($sql);
		
		foreach($arr as $row) {
			$arr_depends[$row['type']][$row['cp']] = $row['description'];
		}
		
		if(count($arr_depends)) {
		
			ksort($arr_depends);
		
			foreach($arr_depends as $type => $arr) {
			
				if($type == 'depend')
					$str = gettext('BUILD DEPENDENCIES');
				else
					$str = gettext('RUNTIME DEPENDENCIES');
			
				if(count($arr)) {
				
					echo "<h4>$str</h4>\n";
				
					echo "<table class='dependencies' cellpadding='4' cellspacing='0'>\n";
					
					$x = 0;
					
					foreach($arr as $cp => $description) {
					
						$class = getRowClass($x++);
						$url = $base_uri.$cp;
					
						echo "\t<tr class='$class'>\n";
						echo "\t\t<td  valign='top'><a href='$url'>$cp</a></td>\n";
						echo "\t\t<td>$description</td>\n";
						echo "\t</tr>\n";
					}
					echo "</table>\n";
				}
			}
		}
		
		if($view == 'package') {
			$sql = "SELECT DISTINCT cp, description FROM view_reverse_depend WHERE package = ".$db->quote($package_id)." ORDER BY cp;";
			$arr = $db->getAssoc($sql);
			
			if(count($arr)) {
			
				$str = gettext("REVERSE DEPENDENCIES");
			
				echo "<h4>$str</h4>\n";
				
				echo "<table class='dependencies' cellpadding='4' cellspacing='0'>\n";
				
				$x = 0;
			
				foreach($arr as $cp => $description) {
				
					$class = getRowClass($x++);
					$url = $base_uri.$cp;
				
					echo "\t<tr class='$class'>\n";
					echo "\t\t<td valign='top'><a href='$url'>$cp</a></td>\n";
					echo "\t\t<td>$description</td>\n";
					echo "\t</tr>\n";
				}
				echo "</table>\n";
				
			}
			
			$arr_licenses = $p->getLicenses();
		
			$url_licenses = array();
			
			/** License **/
			echo "<div id='license' style='display: none;'>\n";
			$str = gettext("LICENSE");
			echo "<h4>$str</h4>\n";
		
			if(count($arr_licenses)) {
				foreach($arr_licenses as $name) {
					$str = nl2br(wordwrap(str_replace('-', ' ', $name), 25));
					$url_licenses[$str] = $base_uri."licenses/".urlencode($name);
				}
			}
			
			$str = "\t\t\t\t\t<ul>\n";
			foreach($url_licenses as $name => $url)
				$str .= "\t\t\t\t\t\t<li><a href='$url'>$name</a></li>\n";
			$str .= "\t\t\t\t\t</ul>\n";
			
			echo $str;
			
			echo "</div>\n";
			
		}
		
		echo "</div>\n";
		
		/** Ebuild Source **/
		if($view == 'ebuild') {
		
			$e = new DBEbuild($ebuild_id);
		
			$sql = "SELECT source FROM ebuild WHERE id = ".$db->quote($ebuild_id).";";
			$source = $db->getOne($sql);
			
			echo "<div id='source' style='display: none;'>\n";
			$str = gettext("SOURCE CODE");
			echo "<h4>$str</h4>\n";
			echo "<div style='margin: 0 25px;'>\n";
			$str = wordwrap($source, 80);
			echo "<pre>$str</pre>";
			echo "</div>\n";
			echo "</div>\n";
			
			$arr_licenses = $e->licenses;
		
			$url_licenses = array();
			
			/** License **/
			echo "<div id='license' style='display: none;'>\n";
			$str = gettext("LICENSE");
			echo "<h4>$str</h4>\n";
		
			if(count($arr_licenses)) {
				foreach($arr_licenses as $name) {
					$str = nl2br(wordwrap(str_replace('-', ' ', $name), 25));
					$url_licenses[$str] = $base_uri."licenses/".urlencode($name);
				}
			}
			
			$str = "\t\t\t\t\t<ul>\n";
			foreach($url_licenses as $name => $url)
				$str .= "\t\t\t\t\t\t<li><a href='$url'>$name</a></li>\n";
			$str .= "\t\t\t\t\t</ul>\n";
			
			echo $str;
			
			echo "</div>\n";
		
		}
		
		
		
		/** Downloads **/
		echo "<div id='downloads' style='display: none;'>\n";
		$str = gettext("DOWNLOADS");
		echo "<h4>$str</h4>\n";
		echo "</div>\n";
		
		if(($view == 'package' && in_array($section, $arr_package_sections)) || ($view == 'ebuild' && in_array($section, $arr_ebuild_sections))) {
			echo "<script type='text/javascript'>\n";
			echo "$('data').update($('$section').innerHTML);";
			echo "</script>\n";
		}
?>
