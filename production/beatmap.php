<?php
	class Beatmap {
		var $approved;
		var $approved_date;
		var $last_update;
		var $artist;
		var $beatmap_id;
		var $beatmapset_id;
		var $bpm;
		var $creator;
		var $difficultyrating;
		var $hit_length;
		var $source;
		var $title;
		var $total_length;
		var $version;
		var $mode;
		
		public function __construct($json = false) {
			if ($json) {
				if (is_array($json)) {
					$this->set($json);
				} else {
					$this->set(json_decode($set));
				}
			}
		}
		
		public function set($data) {
			foreach ($data AS $key => $value) {
				if (is_array($value)) {
					$sub = new Beatmap;
					$sub->set($value);
					$value = $sub;
				}
				$this->{$key} = $value;
			}
		}
		
		public function expose() {
			return get_object_vars($this);
		}
	}
?>