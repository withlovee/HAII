<?php

class APIEmailController extends BaseController {

	public function sendAlert($type) {
		/*
		Sample Input:
		{
			"key": "HAIIEMAILKEY",
			"num": 6,
			"date": "2014-10-14 20:43",
			"rain": [
				{
					"name": "Out of Ranges",
					"stations": [
						"TPTN",
						"PUAA",
						"PPCH"
					]
				},
				{
					"name": "Missing Pattern",
					"stations": [
						"ABCD"
					]
				}
			],
			"water": [
				{
					"name": "Out of Ranges",
					"stations": [
						"WATER"
					]
				}
			]
		}
		*/
		//return Response::json(Input::all());
		$data = Input::all();
		if($type != 'instantly' && $type != 'daily' && $type != 'monthly')
			return Response::json(['error' => 'incorrect type'], 400);
		if($data['key'] != 'HAIIEMAILKEY')
			return Response::json(['error' => 'incorrect key'], 400);

		$users = User::where('report_'.$type, '=', true)->get()->toArray();
		foreach($users as $user){
			Mail::queue('emails.alert', $data, function($message) use ($data, $user) {
				$message->to($user['email'], $user['username']);
				$message->subject('[QC.HAII] '.$data['num'].' Problem(s) Detected at '.$data['date']);
			});
		}
		return Response::json(['success' => true]);
	}

}
