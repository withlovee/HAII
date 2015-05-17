<?php

class Batch extends \Eloquent
{
    protected $table = 'batches';
    protected $fillable = ['data_type', 'problem_type', 'stations', 'all_station', 'start_datetime', 'end_datetime', 'add_datetime', 'finish_datetime', 'status'];

    # Custom Accessor and Mutator

    /**
     * Custom Laravel Accessor for stations field
     * @param $stationsJson String JSON Serialized array of stations
     * @return mixed        Decoded Array of stations
     */
    public function getStationsAttribute($stationsJson) {
        return json_decode($stationsJson);
    }

    /**
     * Custom Laravel Mutator for stations field
     * @param $stations     List of stations
     */
    public function setStationsAttribute($stations) {
        $this->attributes['stations'] = json_encode($stations);
    }

    /**
     * Custom Laravel Accessor for problems field
     * @param $problemsJson     JSON encoded list of problems type
     * @return mixed            Decoded array of problem types
     */
    public function getProblemTypeAttribute($problemsJson) {
        return json_decode($problemsJson);
    }

    /**
     * Custom Laravel Mutator for problems field
     * @param $value           List of problem types
     */
    public function setProblemTypeAttribute($value) {
        $this->attributes['problem_type'] = json_encode($value);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function isCanceled($id) {
        $batch = self::findOrFail($id);
        return $batch->cancel;
    }

    /**
     * Set status of task (waiting, running, fail, canceled)
     * @param $status
     */
    public function setStatus($status) {
        $this->status = $status;
        $this->save();
    }

    /**
     * Record beginning of execution timestamp
     */
    public function stampBeginExecDatetime() {
        $this->begin_exec_datetime = Date('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * Record end of execution timestamp
     */
    public function stampFinishDatetime() {
        $this->finish_datetime = Date('Y-m-d H:i:s');
        $this->save();
    }
}
