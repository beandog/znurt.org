<?php

	class DBUse {

		private $id;
		private $name;
		private $description;
		private $prefix;
		private $cp;
		private $type;

		function __construct($name, $type = 'global', $key = '') {

			$db =& MDB2::singleton();

			$this->name = $name;
			$this->type = $type;

			// Find out as much as we can
			$sql = "SELECT * FROM use WHERE name = ".$db->quote($name)." $where;";
			$row = $db->getRow($sql);

			if(is_array($row) && count($row)) {
				foreach($row as $key => $value)
					$this->$key = $value;
			} else {

				if($this->type == 'local') {
					$this->cp = $key;
				} elseif($this->type == 'expand') {
					$this->prefix = $key;
				}

 				$this->createNew();

			}

		}

		public function __get($var) {
			return $this->$var;
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if(in_array($var, array('name', 'description', 'prefix'))) {

				$arr_update = array(
					$var => $value,
				);

				$db->autoExecute('use', $arr_update, MDB2_AUTOQUERY_UPDATE, "id = ".$db->quote($this->id));

				$this->$var = $value;

			}

		}

		private function createNew() {

			$db =& MDB2::singleton();

			$arr_insert = array('name' => $this->name);

			if($this->type == 'expand' && $this->prefix)
				$arr_insert['prefix'] = $this->prefix;

			$db->autoExecute('use', $arr_insert, MDB2_AUTOQUERY_INSERT);

			$this->id = $db->lastInsertID();

		}

	}

?>
