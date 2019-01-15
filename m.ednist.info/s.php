<?php
	echo "<pre>";
	
	function fn($dir, $s=0){
		// if($s == 3)
			// exit;
		
		$files = glob($dir . "*.php");
		if($files) {
			// print_r($files);
			foreach($files as $f) {
				$str = file_get_contents($f);
				if(strpos($str, '"\x') !== FALSE)
					echo $f . "\n";
				elseif(strpos($str, 'alphabet') !== FALSE)
					echo $f . "\n";
				elseif(strpos($str, 'alphabet') !== FALSE)
					echo $f . "\n";
				elseif(strpos($str, 'GLOBAL') !== FALSE)
					echo $f . "\n";
				elseif(strpos($str, 'function hex2ascii') !== FALSE)
					echo $f . "\n";
				
				elseif(strpos($str, 'function result($data)') !== FALSE)
					echo $f . "\n";
				elseif(strpos($str, 'googlebot|slurp|') !== FALSE)
					echo $f . "\n";
					
				elseif(strpos($str, 'base64_decode("UEsDBAoAAA') !== FALSE)
					echo $f . "\n";
			}
		}
		
		$dirs = glob($dir . "*", GLOB_ONLYDIR);
		foreach($dirs as $d)
			if(!preg_match("/\/media$/", $d))
				fn($d . "/", $s);
	}
	
	fn(__DIR__ . "/");

	// include "\x
	// favicon_
	
	// print_r(glob("./admin/views/*", GLOB_ONLYDIR));