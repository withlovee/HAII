<?php

class Problem extends \Eloquent {
	protected $fillable = [];

	static function groupByBasin() {
		$problems = Problem::where('start_datetime', '>=', date('Y-m-d 00:00'))->with('station')->get();
		$grouped_problems = array();
		// $problems->each(function($p){
		// 	$grouped_problems[] = $p->station_code;
		// });
		// $grouped_problems = array();
		foreach($problems as $problem){
			// return $problem;
			// $grouped_problems[$problem->station->basin][] = $problem;
			$grouped_problems['a'][] = $problem->station->basin;
		}
		return $grouped_problems;
	}

	function station(){
		return $this->hasOne('TeleStation', 'code', 'station_code');
	}

	function full_station_name(){
		$station = $this->station;
		$str = $station->name.' ต.'.$station->tambon_name.' อ.'.$station->amphoe_name.' จ.'.$station->province_name;
		return $str;
	}

	function get_end_time(){
		$this->end_datetime;
		return date('H:i', strtotime($this->end_datetime));
	}
}