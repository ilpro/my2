<?php
	class FBPixel {
		protected $table = '`tbl_fbpixel`';
		
		public function getAll() {
			db::sql("SELECT `fbpixelId`, `fbpixelName` FROM ".$this->table);
			$rows = db::query();
			array_unshift($rows, array('fbpixelId'=>0, 'fbpixelName'=>"--- Нет ---"));
			return $rows;
		}
		
		public function getId($fbpixelId) {
			if($fbpixelId) {
				db::sql("SELECT `fbpixelCode` FROM ".$this->table." WHERE `fbpixelId` = " . $fbpixelId);
				$rows = db::query();
				return ($rows) ? $rows[0]['fbpixelCode'] : false;
			}
			else
				return false;
		}
	}
		