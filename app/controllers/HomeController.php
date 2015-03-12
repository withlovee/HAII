<?php

class HomeController extends BaseController {

	public function index() {
		$data = array();
		$data['rain_problems'] = Problem::recentByBasin('RAIN', 'OR');
		$data['water_problems'] = Problem::recentByBasin('WATER', 'OR');
		$data['stats'] = Problem::yesterdayStat();
		return View::make('home/index', $data);
	}

}
