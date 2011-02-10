<?php

function showtime($timestamp){
	$date = date("j. F Y, G:i", $timestamp);
	return $date;
}

function showdate($timestamp){
	$date = date("j. F Y", $timestamp);
	return $date;
}

function is_today($timestamp){
	if(date("j", $timestamp) == date("j", time()) && date("m", $timestamp) == date("m", time())){
		return true;
	} else {
		return false;
	}
}

?>
