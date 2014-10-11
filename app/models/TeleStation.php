<?php

class TeleStation extends \Eloquent {
	protected $table = 'tele_station';
	protected $fillable = [];

	public static function basins($form = false){
		$results = DB::table('tele_station')
			->select('basin')
			->orderBy('basin', 'asc')
			->groupBy('basin')
			->get();
		$output = array();
		if($form){
			$output[""] = 'ลุ่มน้ำ';
			foreach($results as $result){
				if($result->basin)
					$output[$result->basin] = $result->basin;
			}
		}
		else
			foreach($results as $result)
				$output[] = $result->basin;			
		return $output;
	}

	public static function provinces($form = false){
		$results = DB::table('tele_station')
			->select('province_name')
			->orderBy('province_name', 'asc')
			->groupBy('province_name')
			->get();
		$output = array();
		if($form){
			$output[""] = 'จังหวัด';
			foreach($results as $result){
				if($result->province_name)
					$output[$result->province_name] = $result->province_name;
			}
		}
		else
			foreach($results as $result)
				$output[] = $result->province_name;			
		return $output;
	}

	public static function codes($form = false){
		$results = DB::table('tele_station')
			->select('code')
			->orderBy('code', 'asc')
			->groupBy('code')
			->get();
		$output = array();
		if($form){
			$output[""] = 'รหัสสถานี';
			foreach($results as $result){
				$output[$result->code] = $result->code;
			}
		}
		else
			foreach($results as $result)
				$output[] = $result->code;			
		return $output;
	}
}