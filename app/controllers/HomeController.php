<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index() {
		$data = array();
		$data['rain_problems'] = Problem::recentByBasin('RAIN', 'BD');
		$data['water_problems'] = Problem::recentByBasin('WATER', 'BD');
		$data['stats'] = Problem::yesterdayStat();
		$data['stations'] = TeleStation::all();
		return View::make('home/index', $data);
	}

}
