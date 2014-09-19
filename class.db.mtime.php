<?php

	class DBMtime {

		private $filename;
		private $filemtime;
		private $mtime;

		function __construct($filename) {

			$db =& MDB2::singleton();

			$this->filename = $filename;

			if(file_exists($filename))
				$this->filemtime = filemtime($filename);

			$sql = "SELECT mtime FROM mtime WHERE filename = ".$db->quote($this->filename).";";

			$this->mtime = $db->getOne($sql);

		}

		public function __get($var) {
			return $this->$var;
		}

		public function __set($var, $value) {

			$db =& MDB2::singleton();

			if($var == 'mtime') {

				$udate = $db->getOne("SELECT NOW();");

				if(is_null($this->mtime)) {

					$arr_insert = array(
						'filename' => $this->filename,
						'mtime' => $value,
					);

					$db->autoExecute('mtime', $arr_insert, MDB2_AUTOQUERY_INSERT);
				} else {

					$arr_update = array(
						'mtime' => $value,
						'udate' => $udate,
					);

					$db->autoExecute('mtime', $arr_update, MDB2_AUTOQUERY_UPDATE, "filename = ".$db->quote($this->filename));
				}

			}

		}

	}

?>
