<?php

class TeleStation extends \Eloquent
{
    protected $table = 'tele_station';
    protected $fillable = [];

    /**
     * Get list of basin
     * @param bool $form    convert name to key-value array format to be used with HTML dropdown filter on error_log page
     * @return array
     */
    public static function basins($form = false)
    {
        $results = DB::table('tele_station')
            ->select('basin')
            ->orderBy('basin', 'asc')
            ->groupBy('basin')
            ->get();
        $output = array();
        if ($form) {
            // $output[""] = 'ลุ่มน้ำ';
            $output[""] = "";
            foreach ($results as $result) {
                if ($result->basin) {
                    $output[$result->basin] = $result->basin;
                }
            }
        } else {
            foreach ($results as $result) {
                $output[] = $result->basin;
            }
        }

        return $output;
    }

    /**
     * Get list of provinces
     * @param bool $form    convert name to key-value array format to be used with HTML dropdown filter on error_log page
     * @return array
     */
    public static function provinces($form = false)
    {
        $results = DB::table('tele_station')
            ->select('province_name')
            ->orderBy('province_name', 'asc')
            ->groupBy('province_name')
            ->get();
        $output = array();
        if ($form) {
            // $output[""] = 'จังหวัด';
            $output[""] = "";
            foreach ($results as $result) {
                if ($result->province_name) {
                    $output[$result->province_name] = $result->province_name;
                }
            }
        } else {
            foreach ($results as $result) {
                $output[] = $result->province_name;
            }
        }

        return $output;
    }

    /**
     * Get list of province in basin
     * @param $basin
     * @return array
     */
    public static function provincesByBasin($basin)
    {
        $result = null;
        if ($basin == "all") {
            $results = DB::table('tele_station')
                ->select('province_name')
                ->orderBy('province_name', 'asc')
                ->groupBy('province_name')
                ->get();
        } else {
            $results = DB::table('tele_station')
                ->select('province_name')
                ->where('basin', $basin)
                ->orderBy('province_name', 'asc')
                ->groupBy('province_name')
                ->get();
        }

        $output = array();

        foreach ($results as $result) {
            $output[] = $result->province_name;
        }

        return $output;
    }

    /**
     * Get list of telestations code by province
     * @param $province
     * @return array
     */
    public static function stationCodeByProvince($province)
    {
        $result = null;
        if ($province == "all") {
            $results = DB::table('tele_station')
                ->select('code')
                ->orderBy('code', 'asc')
                ->groupBy('code')
                ->get();
        } else {
            $results = DB::table('tele_station')
                ->select('code')
                ->where('province_name', $province)
                ->orderBy('code', 'asc')
                ->groupBy('code')
                ->get();
        }

        $output = array();

        foreach ($results as $result) {
            $output[] = $result->code;
        }

        return $output;
    }

    /**
     * Get list of parts of Thailand
     * @param bool $form    convert name to key-value array format to be used with HTML dropdown filter on error_log page
     * @return array
     */
    public static function parts($form = false)
    {
        $results = DB::table('tele_station')
            ->select('part')
            ->orderBy('part', 'desc')
            ->groupBy('part')
            ->get();
        $output = array();
        if ($form) {
            // $output[""] = 'ภูมิภาค';
            $output[""] = '';
            foreach ($results as $result) {
                if ($result->part) {
                    $output[$result->part] = $result->part;
                }
            }
        } else {
            foreach ($results as $result) {
                $output[] = $result->part;
            }
        }

        return $output;
    }

    /**
     * Get list of telestation codes
     * @param bool $form    convert name to key-value array format to be used with HTML dropdown filter on error_log page
     * @return array
     */
    public static function codes($form = false)
    {
        $results = DB::table('tele_station')
            ->select('code')
            ->orderBy('code', 'asc')
            ->groupBy('code')
            ->get();
        $output = array();
        if ($form) {
            // $output[""] = 'รหัสสถานี';
            $output[""] = '';
            foreach ($results as $result) {
                $output[$result->code] = $result->code;
            }
        } else {
            foreach ($results as $result) {
                $output[] = $result->code;
            }
        }

        return $output;
    }
}
