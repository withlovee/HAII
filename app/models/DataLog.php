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
				date = DATE '$date' AND time <= TIME '$time')
			");
		}
		return $query;
	}

	public static function setValToNull($problem) {

		$nullVal = -9999;

		$start_unix_timestamp = strtotime($problem->start_datetime);
		$end_unix_timestamp = strtotime($problem->end_datetime);

		$start_date = date('Y-m-d', $start_unix_timestamp);
		$end_date = date('Y-m-d', $end_unix_timestamp);

		$start_time = date('H:i:s', $start_unix_timestamp);
		$end_time = date('H:i:s', $end_unix_timestamp);

		if($problem->data_type == "WATER") {
			DB::statement(DB::raw("
				UPDATE data_log SET water1 = $nullVal
				WHERE
					(date > DATE '$start_date'
					OR date = DATE '$start_date' AND time >= TIME '$start_time')
				AND
					(date < DATE '$end_date'
					OR
					date = DATE '$end_date' AND time <= TIME '$end_time')
			"));
		} else if($problem->data_type == "RAIN") {
			DB::statement(DB::raw("
				UPDATE data_log SET rain1h = $nullVal
				WHERE
					(date > DATE '$start_date'
					OR date = DATE '$start_date' AND time >= TIME '$start_time')
				AND
					(date < DATE '$end_date'
					OR
					date = DATE '$end_date' AND time <= TIME '$end_time')
			"));
		}
 	}

	public static function restoreVal($problem) {

		$start_unix_timestamp = strtotime($problem->start_datetime);
		$end_unix_timestamp = strtotime($problem->end_datetime);

		$start_date = date('Y-m-d', $start_unix_timestamp);
		$end_date = date('Y-m-d', $end_unix_timestamp);

		$start_time = date('H:i:s', $start_unix_timestamp);
		$end_time = date('H:i:s', $end_unix_timestamp);

		if($problem->data_type == "WATER") {
			DB::statement(DB::raw("
				UPDATE data_log SET water1 = origin_water1
				WHERE
					(date > DATE '$start_date'
					OR date = DATE '$start_date' AND time >= TIME '$start_time')
				AND
					(date < DATE '$end_date'
					OR
					date = DATE '$end_date' AND time <= TIME '$end_time')
			"));
		} else if($problem->data_type == "RAIN") {
			DB::statement(DB::raw("
				UPDATE data_log SET rain1h = origin_rain1h
				WHERE
					(date > DATE '$start_date'
					OR date = DATE '$start_date' AND time >= TIME '$start_time')
				AND
					(date < DATE '$end_date'
					OR
					date = DATE '$end_date' AND time <= TIME '$end_time')
			"));
		}
	}

	public static function validDataType($type) {
		return $type == 'WATER' || $type == "RAIN";
	}

}