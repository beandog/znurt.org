<?

	// Get the recent packages for rightnav
	// FIXME use package_recent
	$recent_amount = $amount * 10;
	$sql = "SELECT c.name AS category_name, p.name AS package_name, e.pvr FROM ebuild e INNER JOIN package_recent pr ON e.package = pr.package AND e.cache_mtime = pr.max_ebuild_mtime INNER JOIN package p ON e.package = p.id INNER JOIN category c ON c.id = p.category WHERE e.status = 0 ORDER BY pr.max_ebuild_mtime DESC, e.package, e.ev DESC, e.lvl DESC, e.p IS NULL, e.p DESC, e.rc IS NULL, e.rc DESC, e.pre IS NULL, e.pre DESC, e.beta IS NULL, e.beta DESC, e.alpha IS NULL, e.alpha DESC, e.pr IS NULL, e.pr DESC LIMIT $recent_amount;";
	$arr_recent_packages = $db->getAll($sql);
	
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="">
	<meta name="keywords" content="">
	
	<link rel="stylesheet" href="<?=$base_uri;?>css/master.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? if($lingua == 'es') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/es.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'fr') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/fr.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'de') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/de.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'tr') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/tr.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'cz') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/cz.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'it') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/it.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<? if($lingua == 'el') { ?>
		<link rel="stylesheet" href="<?=$base_uri;?>css/el.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<? } ?>
	<link rel="icon" type="image/vnd.microsoft.icon" href="<?=$base_uri;?>favicon.ico">
	
	<title>gentoo linux ~ <?=$html_title;?></title>
<?

	$title = "recent packages";
	$atom = $base_url."xml/feeds/fresh_ebuilds/atom.xml";
	$rss = $base_url."xml/feeds/fresh_ebuilds/rss.xml";
	
 	echo "<link rel='alternate' type='application/atom+xml' title='the fresh ebuilds (atom)' href='$atom'>\n";
 	echo "<link rel='alternate' type='application/rss+xml' title='the fresh ebuilds (rss)' href='$rss'>\n";
 	
 	if($arch) {
 		$atom = $base_url."xml/feeds/fresh_ebuilds/atom.$arch.xml";
		$rss = $base_url."xml/feeds/fresh_ebuilds/rss.$arch.xml";
		echo "<link rel='alternate' type='application/atom+xml' title='the fresh ebuilds ~ $arch (atom)' href='$atom'>\n";
 		echo "<link rel='alternate' type='application/rss+xml' title='the fresh ebuilds ~ $arch (rss)' href='$rss'>\n";
 	}
 	
 	$atom = $base_url."xml/feeds/new_packages/atom.xml";
	$rss = $base_url."xml/feeds/new_packages/rss.xml";
	
	echo "<link rel='alternate' type='application/atom+xml' title='new packges (atom)' href='$atom'>\n";
 	echo "<link rel='alternate' type='application/rss+xml' title='new packges (rss)' href='$rss'>\n";

// 	if($arch) {
// 		$arch_title = "$title ~ $arch";
// 		$arch_url = "$url?arch=$arch";
// // 		echo "<link rel='alternate' type='application/atom+xml' title='$arch_title' href='$arch_url'>\n";
// 	}

?>

<script src="https://www.google.com/jsapi?key=ABQIAAAAyI6LxePEcQ0oNX5IX2K4YhTvJdjsJxEPNHJyN_PXMNSvZuQykBR54JHiiczDtI81rAIApKrO40k94A" type="text/javascript"></script>
<script type='text/javascript'>
google.load("prototype", "1.6.1.0");
function disableAutocomplete() { if($('searchForm')) { $('searchForm').writeAttribute('autocomplete', 'off'); }}
var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-24554315-1']);
 _gaq.push(['_setDomainName', '.znurt.org']);
 _gaq.push(['_trackPageview']);

 (function() {
   var ga = document.createElement('script'); ga.type =
'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
 })();
</script>
