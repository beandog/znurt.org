<?php

	class DBPackageUse {

		private $id;
		private $package;
		private $use;
		private $description;

		function __construct($package, $use) {

			$db =& MDB2::singleton();

			$this->name = $name;

			// Find out as much as we can
			$sql = "SELECT * FROM package_use WHERE package = ".$db->quote($package)." AND use = ".$db->quote($use).";";
			$row = $db->getRow($sql);

			if(is_array($row) && count($row)) {
				foreach($row as $key => $value)
					$this->$key = $value;
			} else {
				$this->package = $package;
				$this->use = $use;
 				$this->createNew();
			}

		}

		public function __get($var) {
			return $this->$var;
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if(in_array($var, array('package', 'use', 'description'))) {

				$arr_update = array(
					$var => $value,
				);

				$db->autoExecute('package_use', $arr_update, MDB2_AUTOQUERY_UPDATE, "id = ".$db->quote($this->id));

				$this->$var = $value;

			}

		}

		private function createNew() {

			$db =& MDB2::singleton();

			$arr_insert = array(
				'package' => $this->package,
				'use' => $this->use,
			);

			$db->autoExecute('package_use', $arr_insert, MDB2_AUTOQUERY_INSERT);

			$this->id = $db->lastInsertID();

		}

	}

?>
