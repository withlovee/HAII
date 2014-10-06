<?php

class TeleStation extends \Eloquent {
	protected $table = 'tele_station';
	protected $fillable = [];

// 	public static function recentProblemsByBasin(){
// 		return DB::table('tele_station')
// 			->select('basin')
// 			->orderBy('basin', 'asc')
// 			->groupBy('basin')
// 			->get();
// 	}
}