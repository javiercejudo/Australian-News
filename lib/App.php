<?php

class App {

	static public function load_XML() {
		$local_url = LOCAL_DATA;
		if (is_file($local_url) && date('U') - filemtime($local_url) < 60*5+10) {
			$xml_string = file_get_contents($local_url);
		} else {
			$xml_string = file_get_contents(SOURCE);
			file_put_contents($local_url, $xml_string);
		}
		return simplexml_load_string($xml_string);
	}
	
	static public function set_num() {
		if (isset($_GET['ns'])) {
			return DURATION;
		}
		if (isset($_GET['num']) && is_int($_GET['num'])) {
			return $_GET['num'];
		}
		return DURATION;
	}
	
	static public function set_skip() {
		return 0;
	}
	
	static public function set_q() {
		if (isset($_GET['q']) && !empty($_GET['q'])) {
			return trim($_GET['q']);
		}
	}	
}
