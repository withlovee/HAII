<?php

class BatchController extends BaseController
{
    public function index()
    {
    		$stations = TeleStation::codes();
        return View::make('batch/index', compact('stations'));
    }
}
