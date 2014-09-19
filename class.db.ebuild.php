<?php

	class DBEbuild {

		private $package;
		private $arr_db;
		private $arr_cols;
		private $arr_keys;
		private $table;
		private $id;
		private $description;
		private $homepage;
		private $keywords;

		function __construct($id) {

			if(!is_numeric($id))
				$id = 0;
			$this->id = $id;

			$db =& MDB2::singleton();

			$this->table = 'ebuild';

			// Go ahead and query as much as we can
			$sql = "SELECT * FROM view_ebuild WHERE id = $id;";
			$this->arr_db = $db->getRow($sql);

			$this->arr_keys = array_keys($this->arr_db);
			unset($this->arr_keys['id']);

			$this->arr_cols = array('package', 'pf', 'pv', 'pr', 'pvr', 'alpha', 'beta', 'pre', 'rc', 'p', 'version', 'slot', 'portage_mtime', 'cache_mtime', 'status', 'ev', 'lvl');

		}

		public function __get($var) {

			if($var == 'masked') {
				return ( $this->arr_db['masked'] == 't' ? true : false );
			}

			if(is_null($this->$var)) {

				if(in_array($var, $this->arr_keys))
					return $this->arr_db[$var];

				switch($var) {

					case 'description':
						return $this->getDescription();
						break;

					case 'homepage':
						return $this->getHomepage();
						break;

					case 'licenses':
						return $this->getLicenses();
						break;

					case 'keywords':
						return $this->getKeywords();
						break;

				}

			}

			return $this->$var;
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if(in_array($var, $this->arr_cols)) {
				$arr_update = array($var => $value);
				$db->autoExecute($this->table, $arr_update, MDB2_AUTOQUERY_UPDATE, "id = ".$db->quote($this->id));
				$this->arr_db[$var] = $value;
			}
		}

		// Strings
		public function getDescription() {

			$db =& MDB2::singleton();

			$var = 'description';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT TRIM(em.value) FROM ebuild_metadata em INNER JOIN ebuild e ON em.ebuild = e.id AND e.id = ".$this->id." WHERE em.keyword = 'description'ORDER BY e.cache_mtime DESC LIMIT 1;";
			$value = $db->getOne($sql);

			return $this->$var = $value;

		}

		public function getHomepage() {

			$db =& MDB2::singleton();

			$var = 'homepage';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT TRIM(eh.homepage) FROM ebuild_homepage eh INNER JOIN ebuild e ON eh.ebuild = e.id AND e.id = ".$this->id." ORDER BY e.cache_mtime DESC LIMIT 1;";
			$value = $db->getOne($sql);

			return $this->$var = $value;

		}

		public function getLicenses() {

			$db =& MDB2::singleton();

			$var = 'licenses';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT license, name FROM view_licenses WHERE ebuild = ".$this->id." ORDER BY name;";
			$value = $db->getAssoc($sql);

			return $this->$var = $value;

		}

		public function getKeywords() {

			$db =& MDB2::singleton();

			$var = 'keywords';

			if(!is_null($this->$var))
				return $this->$var;

			$sql = "SELECT name, status FROM view_arches WHERE ebuild = ".$this->id." ORDER BY name;";
			$value = $db->getAssoc($sql);

			return $this->$var = $value;

		}

	}

?>
