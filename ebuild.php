<?
	
	if($ebuild_id) {
	
		echo "<h4>Ebuild</h4>";
	
		echo keywordsRow(array($ebuild_id), 'ebuild');
		
		require_once 'class.db.ebuild.php';
		require_once 'inc.package.php';
		
		
	}
	
	
?>