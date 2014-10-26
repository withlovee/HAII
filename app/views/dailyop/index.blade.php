@extends('errorlog.index', ['title' => $title])

@section('page-title')
 Daily Operations<br>
 <small>แสดงปัญหาที่เกิดขึ้นตั้งแต่วันที่ {{ thai_date(getTime()) }} {{ date('Y', getTime()) }} เวลา 7.01 น.</small>
@stop