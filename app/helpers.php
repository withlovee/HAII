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

	$map = array(
			'OR' => 'Out of Range',
			'MG' => 'Missing Gap',
			'FV' => 'Flat Value',
			'OL' => 'Outliers',
			'HM' => 'Inhomogenity',
			'MP' => 'Missing Pattern'
		);
	
	return $map[$name];
}
function getErrorButton($id, $error, $default = '') {

	$class = '';
	if($error == $default) {
		$class = ' active';
	}
		
	if($error == "true")
		return '<a href="#" data-error="true" data-id="'.$id.'" class="update error'.$class.'"><span class="glyphicon glyphicon-exclamation-sign"></span><!--<span class="text">Error</span></a>-->';
	else if($error == "false")
		return '<a href="#" data-error="false" data-id="'.$id.'" class="update noterror'.$class.'"><span class="glyphicon glyphicon-ok-sign"></span><!--<span class="text">Not Error</span></a>-->';
	else
		return '<a href="#" data-error="undefined" data-id="'.$id.'" class="update undefined'.$class.'"><span class="glyphicon glyphicon-question-sign"><!--</span> <span class="text">Undefined</span></a>-->';
}

function isAdmin() {
	return Auth::user()->role == 'Admin';
}