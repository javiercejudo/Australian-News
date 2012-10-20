<?php

require_once DIRLIB . 'DB.php';

class App {

	function __construct(&$num, &$skip, &$q) {
		$num  = self::set_num();
		$skip = self::set_skip();
		$q    = self::set_q();
	}
	
	private function set_num() {
		if (isset($_GET['ns'])) {
			return DURATION;
		}
		if (isset($_GET['num']) && !empty($_GET['num']) && intval($_GET['num']) > 0) {
			return $_GET['num'];
		}
		return DURATION;
	}
	
	private function set_skip() {
		if (isset($_GET['skip']) && !empty($_GET['skip']) && intval($_GET['skip']) > 0) {
			return $_GET['skip'];
		}
		return 0;
	}
	
	private function set_q() {
		if (isset($_GET['q']) && !empty($_GET['q'])) {
			return trim($_GET['q']);
		}
	}

	static public function load_XML() {
		$local_url = LOCAL_DATA;
		if (is_file($local_url) && date('U') - filemtime($local_url) < 60*10-10) {
			//$xml_string = file_get_contents($local_url);
		} else {
			$xml_string = file_get_contents(SOURCE);
			file_put_contents($local_url, $xml_string);
			$xml = simplexml_load_string($xml_string);
			DB::do_insert($xml);
		}
	}
}
