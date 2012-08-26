<?php
class News {	
	private $title;
	private $description;
	private $pub_date;
	private $link;
	private $guid;
	private $created;
	
	public function __construct($item) {
		$this->title       = $item->title;
		$this->description = $item->description;
		$this->pub_date    = $item->pub_date;
		$this->link        = $item->link;
		$this->guid        = $item->guid;
		$this->created     = $item->created;
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
