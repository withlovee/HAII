<?php

class APITeleStationController extends BaseController {

  public function waterLevelDetail () {

    $stationCode = Input::get('station');

    $station = TeleWaterLevelDetail::where('code', $stationCode)->first();

    return Response::json($station);
  }

}