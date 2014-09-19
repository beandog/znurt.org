<?php

	class DBCategory {

		private $id;
		private $name;
		private $arr_keys;
		private $table;
		private $arr_db;
		private $description;

		function __construct($id) {

			if(!is_numeric($id))
				$id = 0;
			$this->id = $id;

			$db =& MDB2::singleton();
			$this->table = 'category';

			// Go ahead and query as much as we can
			$sql = "SELECT * FROM ".$this->table." WHERE id = ".$db->quote($this->id).";";
			$this->arr_db = $db->getRow($sql);

			$this->arr_keys = array_keys($this->arr_db);
			unset($this->arr_keys['id']);

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
				$db->autoExecute($this->table, $arr_update, MDB2_AUTOQUERY_UPDATE, "id = ".$db->quote($this->id));
				$this->$var = $value;
			}
		}

		public function getDescription($lingua = "en") {

			if($this->description)
				return $this->description;

			$db =& MDB2::singleton();

			$sql = "SELECT description FROM category_description WHERE category = ".$db->quote($this->id)." AND lingua = ".$db->quote($lingua).";";

			$this->description = $db->getOne($sql);

			return $this->description;

		}

	}

?>
