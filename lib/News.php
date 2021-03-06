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
		$this->pub_date    = ($from_db === true) ? 
		                     $item->pub_date : 
		                     $item->pubDate;
		$this->link        = $item->link;
		$this->guid        = $item->guid;
		$this->created     = $item->created;
	}

	public function title() {
		return $this->title;
	}

	public function description() {
		return $this->description;
	}

	public function pub_date() {
		return $this->pub_date;
	}

	public function link() {
		return $this->link;
	}

	public function guid() {
		return $this->guid;
	}

	public function created() {
		return $this->created;
	}

	public function clean_description() {
		if (strpos($this->description,'<font face=')) 
		{
			return preg_replace("/<p><img[^>]+\><\/p>/i","",substr($this->description,0,strpos($this->description,'<font face=')));
		}
		elseif (strpos($this->description,'<img width=')) 
		{
			return preg_replace("/<p><img[^>]+\><\/p>/i","",substr($this->description,0,strpos($this->description,'<img width=')));
		}
		else
		{
			return preg_replace("/<p><img[^>]+\><\/p>/i", "", $this->description);
		}
	}

	static public function get_item_level ($xml) {
		return $xml->channel->item;
	}
}
