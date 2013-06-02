<?php

session_start();

require_once 'include/config.php';
require_once 'include/functions.php';

$_SESSION['auth'] = true;
$auth = $_SESSION['auth'];

if (!$auth) :

	_ws_showerror('access denied!');
	exit;
endif;

if($_FILES)
	$files=		$_POST['files'];

if($_POST):

	$action=	explode(':', $_POST['action']);

elseif($_GET):

	$action=	explode(':', $_GET['action']);

endif;

mysql_select_db($_WS['SQL_DBNAME'][0], $_WS['SQL_CONNECT'][0]) or die('Could not select database.');

switch ($action[0]) {

	case 'create_sharepoint' :

		$return = false;
		for ($s_hash=_ws_mkhash(); $return == true; $s_hash=_ws_mkhash()) :

			$query = mysql_query("SELECT s_id FROM shares WHERE s_hash = '" . $s_hash . "'");
			if (mysql_num_rows($query)==0) $return= true;
		endfor;

		if (_ws_preg_job_no(strtoupper($_POST['job_no']))):
			
			$job_no = strtoupper($_POST['job_no']);
		else :

			_ws_showerror('wrong job# pattern!');
			exit;
		endif;

		$job_name = $_POST['job_name'];
		$admin = (!empty($_POST['admin']))? strtolower($_POST['admin']) : NULL;

		mysql_query("INSERT INTO shares (
			s_hash,
			job_no,
			job_name,
			admin
		) VALUES (
			'" . $s_hash . "',
			'" . $job_no . "',
			'" . $job_name . "',
			'" . $admin ."'
		)");

		if(!mysql_error())
			_ws_log('Sharepoint ' . $s_hash . ' created');
			$_SESSION['action_return'] = 'New Sharepoint ' . $job_no . ' was created!';

		break;

	case 'label':

		foreach ($_POST['f_hash'] as $hash) {

			$query = "UPDATE files SET label=" . $action[1] . ", time_change=" . time() . " WHERE f_hash = '" . $hash . "'";
			mysql_query($query);
			_ws_log('File ' . $hash . ' labled ' . $action[1] . '');

		}

		break;

	case 'status':		/*	-------------------- DELETE -------------- */

		foreach ($_POST['f_hash'] as $hash) {

			$query = "UPDATE files SET status=" . $action[1] . ", time_change=" . time() . " WHERE f_hash = '" . $hash . "'";
			mysql_query($query);

		}

		break;

	case 'allow_upload':		/*	-------------------- DELETE -------------- */

		$query = "UPDATE shares SET allow_upload=" . $action[2] . " WHERE s_hash = '" . $action[1] . "'";
		mysql_query($query);

		break;

	default:
		_ws_showerror('no valid command found!');
		exit;
		break;
}

if(mysql_error()) :
	echo mysql_error();

else :
	header('Location: ' . @$_SERVER['HTTP_REFERER']);

endif;
?>