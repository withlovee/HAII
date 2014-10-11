<?php

class Problem extends \Eloquent {
	protected $fillable = [];

	public function scopeDataType($query, $type){
		return $query
			->join('tele_station', 'problems.station_code', '=', 'tele_station.code')
			->select('problems.id', 'code', 
				'name', 
				'problem_type',
				'tambon_name', 
				'amphoe_name', 
				'province_name', 
				'basin', 
				'start_datetime', 
				'end_datetime', 
				'num', 
				'problems.status'
				)
			->where('data_type', '=', $type);
	}
	public function scopeBasin($query, $basin){
		if($basin) return $query->where('basin', '=', $basin);
		return $query;
	}
	public function scopeCode($query, $code){
		if($code) return $query->where('code', '=', $code);
		return $query;
	}
	public function scopeProblem($query, $problem_type){
		if($problem_type) return $query->where('problem_type', '=', $problem_type);
		return $query;
	}

	static function recentByBasin($data_type, $problem_type = 'BD') {
		/*-- Query latest problems with tele_station information --*/
		$problems = DB::table('problems')
			->join('tele_station', 'problems.station_code', '=', 'tele_station.code')
			->select('code', 'name', 'tambon_name', 'amphoe_name', 'province_name', 'basin','start_datetime', 'end_datetime', 'num', 'problems.status')
			->where('data_type', '=', $data_type)
			->where('problem_type', '=', $problem_type)
			->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01'))
			->where('problems.status', '=', 'undefined')
			->get();
		/*-- Group the data by basins --*/
		$grouped_problems = array();
		foreach($problems as $problem){
			$item = (array) $problem;
			$item['full_name'] = self::build_full_station_name($item);
			$item['end_time'] = date('H:i', strtotime($item['end_datetime']));
			if($item['basin']){
				$grouped_problems[$item['basin']][] = $item;
			}
			/*-- If the station has no basin information --*/
			else{
				$grouped_problems['none'][] = $item;
			}
		}
		return $grouped_problems;
	}

	static function allForTable($params){
		$problems = self::dataType($params['data_type'])
			->basin($params['basin'])
			->code($params['code'])
			->problem($params['problem_type'])
			->get();
		foreach($problems as $problem){
			$problem['station_name'] = self::build_full_station_name($problem);
			unset($problem['station']);
			$problem['problem_type'] = self::getTypeName($problem['problem_type']);
		}
		return $problems;
	}

	static function getTypeName($name){
		switch($name){
			case 'BD':
				return 'Out-of-Range';
			default:
		}
	}

	static function yesterdayStat(){
		$results['RAIN'] = DB::table('problems')
			->select(DB::raw('problem_type, count(*)'))
			->where('data_type', '=', 'RAIN')
			->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->groupBy('problem_type')
			->get();
		$results['WATER'] = DB::table('problems')
			->select(DB::raw('problem_type, count(*)'))
			->where('data_type', '=', 'WATER')
			->where('start_datetime', '>=', self::getStartDate('Y-m-d 07:01', -1))
			->where('start_datetime', '<=', self::getStartDate('Y-m-d 07:00'))
			->groupBy('problem_type')
			->get();
		$output = array();
		foreach($results as $type => $r)
			foreach($r as $result)
				$output[$type][$result->problem_type] = $result->count;
		return $output;
	}

	private static function getStartDate($format, $offset = 0) {
		if(intval(date('G')) < 7 || (intval(date('G')) == 7) && intval(date('i')) == 0) $offset -= 1;
		return date($format, time()+($offset*24*60*60));
	}

	function station(){
		return $this->hasOne('TeleStation', 'code', 'station_code');
	}

	private static function build_full_station_name($data){
		return $data['name'].' ต.'.$data['tambon_name'].' อ.'.$data['amphoe_name'].' จ.'.$data['province_name'];
	}

	function full_station_name(){
		$station = $this->station;
		return self::build_full_station_name((array) $station);
	}

	private static function build_end_time($end_datetime){
		return date('H:i', strtotime($end_datetime));
	}

	function get_end_time(){
		return self::build_end_time($this->end_datetime);
	}
}