<?php

class Batch extends \Eloquent
{
    protected $table = 'batches';
    protected $fillable = ['data_type', 'problem_type', 'stations', 'all_station', 'start_datetime', 'end_datetime', 'add_datetime', 'finish_datetime', 'status'];

    # Custom Accessor and Mutator
    public function getStationsAttribute($value) {
        return json_decode($value);
    }

    public function setStationsAttribute($value) {
        $this->attributes['stations'] = json_encode($value);
    }

    public function getProblemTypeAttribute($value) {
        return json_decode($value);
    }

    public function setProblemTypeAttribute($value) {
        $this->attributes['problem_type'] = json_encode($value);
    }
}
