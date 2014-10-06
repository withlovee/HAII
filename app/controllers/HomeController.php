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

	public function showWelcome()
	{
		// var_dump(Auth::user());
		$data = array();

		// $data['problems'] = Problem::where('start_datetime', '>=', date('Y-m-d 00:00'))->with('station')->grouped_by_basin();
		$data['problems'] = Problem::groupByBasin();
		// $data['basins'] = TeleStation::recentProblemsByBasin();
		$data['stations'] = TeleStation::all();
		// var_dump('<br><br><br><br><br><br><br>');
		// var_dump($users);
		return View::make('home/index', $data);
		// $results = DB::select('select * from test where id = ?', array(2));
		// var_dump($results);
		// return '';
	}

	// public function 

}
