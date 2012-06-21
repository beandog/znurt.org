<?
	
	require_once 'class.db.category.php';	
	
	if($package_id) {
	
		$db_package = new DBPackage($package_id);
		$c = new DBCategory($db_package->category);
		
		$category_name = $c->name;
		
// 		Common::pre($db_package->arr_db);
	
		$str = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<package>
</package>
XML;

 		$sxe = new SimpleXMLElement($str);
 		$child = $sxe->addChild('category');
 		$child->addAttribute('name', $category_name);
 		
 		$child = $sxe->addChild('ebuild');
 		$child->addAttribute('pf', '1.2.0');
 		
 		$sql = "SELECT DISTINCT category_name, package_name FROM view_reverse_depend WHERE package = ".$db->quote($package_id)." ORDER BY category_name, package_name;";
		$arr = $db->getAll($sql);
		
		if(count($arr)) {
		
			
		
			foreach($arr as $row) {
				extract($row);
				$child = $sxe->addChild('dependency');
				$child->addChild('type', 'reverse');
				$child->addChild('category_name', $category_name);
				$child->addChild('package_name', $package_name);
			}
				
		}
 		
 		$sxe->addChild('description', htmlspecialchars($db_package->description));
//  		$sxe->addChild('changelog', htmlspecialchars($db_package->changelog));
 		
 		
		echo $sxe->asXML();
 		
			

		
	}
	
	
	
	
?>