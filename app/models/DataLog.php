<?php

/**
 * Class DataLog
 */
class DataLog extends \Eloquent
{
    protected $table = 'data_log';
    protected $fillable = [];

    /**
     * Filter by telestation code
     * @param query  $query     Query object
     * @param string $code      Telestation Code
     * @return mixed            New query object
     */
    public function scopeCode($query, $code)
    {
        if ($code) {
            return $query->where('code', '=', $code)->orderBy('date', 'asc')->orderBy('time', 'asc');
        }

        return $query;
    }

    /**
     * Scope only not-null data
     * @param query     $query  Query object
     * @param string    $type   Data type ("WATER", "RAIN")
     * @return mixed            New query object
     */
    public function scopeValid($query, $type)
    {
        if ($type == 'WATER') {
            return $query->whereNotNull('water1');
        } elseif ($type == 'RAIN') {
            return $query->whereNotNull('rain1h');
        }

        return $query;
    }

    /**
     * Scope only data without machine error and without data marked as real error (-9999)
     * @param query     $query  Query Object
     * @param string    $type   Data type ("WATER", "RAIN")
     * @return mixed            new query object
     */
    public function scopeClean($query, $type)
    {
        if ($type == 'WATER') {
            return $query->where('water1', '!=', '999999')
                                     ->where('water1', '!=', '-9999');
        } elseif ($type == 'RAIN') {
            return $query->where('rain1h', '!=', '999999')
                                     ->where('rain1h', '!=', '-9999');
        }
    }

    /**
     * Scope only original data without machine error and without data marked as real error (-9999)
     * @param query     $query  Query Object
     * @param string    $type   Data type ("WATER", "RAIN")
     * @return mixed            new query object
     */
    public function scopeCleanOrigin($query, $type)
    {
        if ($type == 'WATER') {
            return $query->where('origin_water1', '!=', '999999')
                                     ->where('origin_water1', '!=', '-9999');
        } elseif ($type == 'RAIN') {
            return $query->where('origin_rain1h', '!=', '999999')
                                     ->where('origin_rain1h', '!=', '-9999');
        }
    }

    /**
     * Scope only data at the start of the hour
     * @param query     $query  Query Object
     * @return mixed            new query object
     */
    public function scopeHourly($query)
    {
        return $query->whereRaw("EXTRACT(MINUTE FROM time) = 0");
    }

    /**
     * Scope only data from datetime
     * @param query     $query      Query object
     * @param date      $datetime   Start datetime
     * @param int       $offset     Time offset before start datetime
     * @return mixed                New query object
     */
    public function scopeFrom($query, $datetime, $offset = 7200)
    {
        if ($datetime) {
            $unix_timestamp = strtotime($datetime) - $offset;
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

    /**
     * Scope only data before datetime
     * @param query     $query      Query object
     * @param date      $datetime   End datetime
     * @param int       $offset     Time offset after end datetime
     * @return mixed                New query object
     */
    public function scopeTo($query, $datetime, $offset = 7200)
    {
        if ($datetime) {
            $unix_timestamp = strtotime($datetime) + $offset;
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

    /**
     * Set value of data to -9999 to mark as error
     * @param Problem   $problem    problem that is defined as real error
     */
    public static function setValToNull($problem)
    {
        $nullVal = -9999;

        $start_unix_timestamp = strtotime($problem->start_datetime);
        $end_unix_timestamp = strtotime($problem->end_datetime);

        $start_date = date('Y-m-d', $start_unix_timestamp);
        $end_date = date('Y-m-d', $end_unix_timestamp);

        $start_time = date('H:i:s', $start_unix_timestamp);
        $end_time = date('H:i:s', $end_unix_timestamp);

        if ($problem->data_type == "WATER") {
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
        } elseif ($problem->data_type == "RAIN") {
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

    /**
     * Set value of data to its original value to mark as not-error or undefined
     * @param Problem   $problem    problem that is defined as not error or undefined
     */
    public static function restoreVal($problem)
    {
        $start_unix_timestamp = strtotime($problem->start_datetime);
        $end_unix_timestamp = strtotime($problem->end_datetime);

        $start_date = date('Y-m-d', $start_unix_timestamp);
        $end_date = date('Y-m-d', $end_unix_timestamp);

        $start_time = date('H:i:s', $start_unix_timestamp);
        $end_time = date('H:i:s', $end_unix_timestamp);

        if ($problem->data_type == "WATER") {
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
        } elseif ($problem->data_type == "RAIN") {
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

    /**
     * check if data type is "WATER" or "RAIN"
     * @param string    $type     type of data
     * @return bool               is valid data type
     */
    public static function validDataType($type)
    {
        return $type == 'WATER' || $type == "RAIN";
    }
}
