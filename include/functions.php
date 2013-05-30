<?php

function _ws_mkhash() {

	$hash= md5(microtime());
	$hash= substr($hash, 13, -13);
	return $hash;
}

function _ws_file_label($flip= false) {

	$label = array(
		0 => 'none',
		1 => 'red',
		2 => 'orange',
		3 => 'yellow',
		4 => 'green',
		5 => 'blue',
		6 => 'purple',
		7 => 'black'
	);

	if (!$flip)
		return $label;

	else
		array_flip($label);
}

function _ws_file_status($flip= false) {

	$status = array(
		-1 => 'delete',
		0 => 'hide',
		1 => 'show'
	);

	if (!$flip)
		return $status;

	else
		array_flip($status);

}

function _ws_showtime($time=0) {

	$diff=	time() - $time;

	if($diff < 60)
		return $diff . ' seconds ago';

	elseif($diff < 120)
		return '1 minute ago';

	elseif($diff < 3600)
		return floor($diff/60) . ' minutes ago';

	elseif($diff < 86400)
		return floor($diff/3600) . ' hour(s) ago';
	else
		return date('Y-m-d H:i', $time) . ' Uhr';
	// return $r;
}

function _ws_showerror($copy = 'An Error Occured!') {

	$copy = '<div style="width:100%;text-align:center;margin:50px auto;font-family:Courier;"><span style="background:red;padding:5px 10px;color:#FFF;border-radius:7px;">' . strtoupper($copy) . '</span></div>';
	echo $copy;

}

function _ws_auth_by_ip($sheme = '192.168.*.*') {

	global $_SERVER;

	$match=	0;
	$sheme=	explode('.', $sheme);
	$raddr=	explode('.', $_SERVER['REMOTE_ADDR']);

	foreach ($sheme as $key => $value)
		if ($value != '*' && $sheme[$key] != $raddr[$key])
			$match++;

	if ($match > 0)
		return false;

	else
		return true;
}

function _ws_preg_hash( $hash, $ewe= false ) {

	$reg_ex= '/^[a-z0-9]{6}$/';
	return preg_match($reg_ex, $hash);
}

function _ws_preg_job_no( $job_no, $ewe= false ) {

	$reg_ex= '/^WA-[A-Z0-9]{4}-[0-9]{2}-[0-9]{3}$/';
	return preg_match($reg_ex, $job_no);
}

function _ws_preg_email ( $email, $ewe= false ) {

	$reg_ex= '/^([\w-\.]+)@((?:[\w]+\.)+)([a-z]{2,4})$/';
	return preg_match($reg_exp, $email);

}

function _ws_log( $string, $track= true ) {

	global $_SERVER;

	$v[]=	date('Y-m-d H:i:s');
	$v[]=	$string;

	if($track)
		$v[]=	$_SERVER['REMOTE_ADDR'];

	if(!file_put_contents('./logs/ws_log_' . date('Y-m') . '.log' , implode('; ', $v) . "\n", FILE_APPEND))
		exit('logging error!');
}

?>