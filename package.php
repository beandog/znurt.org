<?
	
	if($package_id) {
	
		$str = gettext("PACKAGE");
		echo "<h4>$str</h4>";

		$sql = "SELECT id FROM ebuilds WHERE package = ".$db->quote($package_id).";";
		$arr = $db->getCol($sql);
		
		if(count($arr))
			echo keywordsRow($arr, 'package');
		
		require_once 'inc.package.php';
		
	}
	
?>
<script type='text/javascript'>$('data').update($('changelog').innerHTML);</script>
