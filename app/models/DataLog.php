<?php

class DataLog extends \Eloquent {
	protected $table = 'data_log';
	protected $fillable = [];

	public function scopeCode($query, $code){
		if($code) return $query->where('code', '=', $code)->orderBy('date', 'asc')->orderBy('time', 'asc');
		return $query;
	}

	public function scopeValid($query, $type){
		if($type == 'WATER') return $query->whereNotNull('water1');
		elseif($type == 'RAIN') return $query->whereNotNull('rain1h');
		return $query;
	}

	public function scopeFrom($query, $datetime){
		if($datetime) {
			$unix_timestamp = strtotime($datetime) - (2*60*60);
			$date = date('Y-m-d', $unix_timestamp);
			$time = date('H:i:s', $unix_timestamp);
			return $query->whereRaw("
				(date > DATE '$date'
				OR
				date = DATE '$date' AND time >= TIME '$time')
			");
		}
		return $query;
	}

	public function scopeTo($query, $datetime){
		if($datetime) {
			$unix_timestamp = strtotime($datetime) + (2*60*60);
			$date = date('Y-m-d', $unix_timestamp);
			$time = date('H:i:s', $unix_timestamp);
			return $query->whereRaw("
				(date < DATE '$date'
				OR
				date = DATE '$date' AND time < TIME '$time')
			");
		}
		return $query;
	}
}