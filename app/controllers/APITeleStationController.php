<?php

class APITeleStationController extends BaseController
{
    public function waterLevelDetail()
    {
        $stationCode = Input::get('station');

        $station = TeleWaterLevelDetail::where('code', $stationCode)->first();

        return Response::json($station);
    }

    public function provincesByBasin()
    {
        $basin = Input::get('basin');
        $provinces = array();
        if (is_array($basin)) {
            foreach ($basin as $b) {
                $provinces = array_merge($provinces, TeleStation::provincesByBasin($b));
            }
        } else {
            $provinces = array_merge($provinces, TeleStation::provincesByBasin($basin));
        }

        return Response::json($provinces);
    }

    public function stationCodeByProvince()
    {
        $province = Input::get('province');

        $stationCode = array();
        if (is_array($province)) {
            foreach ($province as $p) {
                $stationCode = array_merge($stationCode, TeleStation::stationCodeByProvince($p));
            }
        } else {
            $stationCode = array_merge($stationCode, TeleStation::stationCodeByProvince($province));
        }

        return Response::json($stationCode);
    }
}
