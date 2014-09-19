<?php

	class DBPackageManifest {

		private $package;
		private $name;
		private $table;
		private $arr_keys;
		private $arr_db;

		function __construct($package) {

			if(!is_numeric($package))
				$package = 0;

			$db =& MDB2::singleton();
			$this->table = 'package_manifest';
			$this->package = $package;

			// Go ahead and query as much as we can
			$sql = "SELECT * FROM ".$this->table." WHERE package = ".$db->quote($package).";";
			$this->arr_db = $db->getRow($sql);

			$this->arr_keys = array_keys($this->arr_db);
			unset($this->arr_keys['package']);

		}

		public function __get($var) {
			if(in_array($var, $this->arr_keys)) {
				return $this->arr_db[$var];
			} else {
				return $this->$var;
			}
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if(in_array($var, $this->arr_keys)) {
				$arr_update = array($var => $value);
				$db->autoExecute($this->table, $arr_update, MDB2_AUTOQUERY_UPDATE, "package = ".$db->quote($this->package));
				$this->arr_db[$var] = $value;
			}
		}

	}

?>
