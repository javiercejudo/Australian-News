<?php
class News {
	private $title;
	private $description;
	private $pub_date;
	private $link;
	private $guid;
	private $created;

	public function __construct($item, $from_db = true) {
		$this->title       = $item->title;
		$this->description = $item->description;
		$this->link        = $item->link;
		$this->guid        = $item->guid;
		$this->created     = $item->created;
		$this->pub_date    = ($from_db === true) ? 
		                     $item->pub_date : 
		                     $item->pubDate ;
	}

	function title() {
		return $this->title;
	}

	function description() {
		return $this->description;
	}

	function pub_date() {
		return $this->pub_date;
	}

	function link() {
		return $this->link;
	}

	function guid() {
		return $this->guid;
	}

	function created() {
		return $this->created;
	}
}
