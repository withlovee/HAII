<?php

class TestController extends BaseController {

  function test() {
    $id = 23035;
    $p = Problem::find($id)->first();

    $d = DataLog::code($p->station_code)
          ->from($p->start_datetime)
          ->to($p->end_datetime)
          ->get();

    // not work, no primary key. need manual query 
    DataLog::setValToNull($p);


    $res = array(
        'p' => $p,
        'd' => $d
      );
    //return Response::json($res);
    return "<pre>".print_r($res, true)."</pre>";
  }

  function test2() {
    $id = 23035;
    $p = Problem::find($id)->first();

    $d = DataLog::code($p->station_code)
          ->from($p->start_datetime)
          ->to($p->end_datetime)
          ->get();

    // not work, no primary key. need manual query 
    DataLog::restoreVal($p);

    $res = array(
        'p' => $p,
        'd' => $d
      );
    //return Response::json($res);
    return "<pre>".print_r($res, true)."</pre>";
  }

}

