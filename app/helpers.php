<?php 

function thai_date($date = null){
	if(!$date) $date = getTime();
	$TH_Day = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
	$TH_Month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	$nDay = date("w", $date);
	$nMonth = date("n", $date)-1;
	$date = date("j", $date);
	$y = date("Y", $date)+543;
	$y = '';
	// return getTime();
	return "$date $TH_Month[$nMonth] $y";
}
function getTime($offset = 0) {
	if(intval(date('G')) < 7 || (intval(date('G')) == 7) && intval(date('i')) == 0) $offset -= 1;
	return time()+($offset*24*60*60);
}
function getProblemName($name) {
	switch($name){
		case 'BD':
			return 'Out-of-Range';
		default:
	}	
}
function getErrorButton($id, $is_error, $default = '') {
	if($default == 'true' && $is_error ||
		$default == 'false' && !$is_error)
		$class = ' active';
	else
		$class = '';
	if($is_error)
		return '<a href="#" data-error="true" data-id="'.$id.'" class="update'.$class.'"><span class="glyphicon glyphicon-ok"></span> <span class="text">Error</span></a>';
	else
		return '<a href="#" data-error="false" data-id="'.$id.'" class="update'.$class.'"><span class="glyphicon glyphicon-remove"></span> <span class="text">Not Error</span></a>';
}