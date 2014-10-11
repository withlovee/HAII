<?php 

function thai_date($date){
	if(!$date) $date = getTime();
	$TH_Day = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
	$TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

	$nDay = date("w", $date);
	$nMonth = date("n", $date)-1;
	$date = date("j", $date);
	$year = date("Y", $date)+543;

	echo("$date $TH_Month[$nMonth] $year");
}
function getTime($offset = 0) {
	if(intval(date('G')) < 7 || (intval(date('G')) == 7) && intval(date('i')) == 0) $offset -= 1;
	return time()+($offset*24*60*60);
}