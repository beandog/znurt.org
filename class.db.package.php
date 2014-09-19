<?php

	class DBPackage {

		private $id;
		private $name;
		private $table;
		private $arr_keys;
		private $arr_db;
		private $changelog;
		private $recent_changes;

		function __construct($id) {

			if(!is_numeric($id))
				$id = 0;

			$db =& MDB2::singleton();
			$this->table = 'package';

			// Go ahead and query as much as we can
			$sql = "SELECT * FROM ".$this->table." WHERE id = ".$db->quote($id).";";
			$this->arr_db = $db->getRow($sql);

			$this->arr_keys = array_keys($this->arr_db);
			unset($this->arr_keys['id']);

			$this->id = $id;

		}

		public function __get($var) {
			if(in_array($var, $this->arr_keys)) {
				return $this->arr_db[$var];
			} else {

				switch($var) {
					case 'changelog':
						return $this->getChangelog();
						break;

					case 'recent_changes':
						return $this->getRecentChanges();
						break;
				}

				return $this->$var;
			}
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if(in_array($var, $this->arr_keys)) {
				$arr_update = array($var => $value);
				$db->autoExecute($this->table, $arr_update, MDB2_AUTOQUERY_UPDATE, "id = ".$db->quote($this->id));
				$this->arr_db[$var] = $value;
			}
		}

		public function getLicenses() {

			$db =& MDB2::singleton();

			$var = 'licenses';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT DISTINCT license, name FROM view_licenses WHERE package = ".$this->id." ORDER BY name;";
			$value = $db->getAssoc($sql);

			return $this->$var = $value;

		}

		public function getChangelog() {

			$db =& MDB2::singleton();

			$var = 'changelog';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT $var FROM package_changelog WHERE package = ".$this->id.";";
			$value = $db->getOne($sql);

			return $this->$var = $value;

		}

		public function getRecentChanges() {

			$db =& MDB2::singleton();

			$var = 'recent_changes';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT $var FROM package_changelog WHERE package = ".$this->id.";";
			$value = $db->getOne($sql);

			return $this->$var = $value;

		}

	}

?>
