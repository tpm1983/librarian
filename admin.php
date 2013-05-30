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

mysql_select_db($_WS['SQL_DBNAME'][0], $_WS['SQL_CONNECT'][0]) or die('Could not select database.');

$query = "SELECT * FROM shares ORDER BY job_name ASC";
$query = mysql_query($query);

while ($share = mysql_fetch_assoc($query)) {
	$shares[] = $share;
}

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=0.9, maximum-scale=0.9">
	<title>Webshare Administration</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>

<body>
<div id="base_wrapper">
	<div id="base_wrapper_admin">

		<?if(!empty($shares)):?>
		<div class="view" id="list_view">
			<table width="100%">
				<tr class="header">
					<th class="link">
						<div>
							Webshare Link
						</div>
					</th>
					<th class="job_no">
						<div>
							Job-/Projektnummer
						</div>
					</th>
					<th class="job_name">
						<div>
							Job-/Projektname
						</div>
					</th>
					<th class="admin">
						<div>
							E-Mail Adresse
						</div>
					</th>
				</tr>
				<?foreach($shares as $key => $share):?>

				<tr class="content">
					<td class="link first-child">
						<div style="font-family:courier new">
							<a href="<?php echo $_WS['BASE_URI'] . $share['s_hash'] ?>" target="_blank"><?php echo $_WS['BASE_URI'] . $share['s_hash'] ?></a>
						</div>
					</td>
					<td class="job_no">
						<div style="font-family:courier new">
							<?=strtoupper($share['job_no']);?>
						</div>
					</td>
					<td class="job_name">
						<div style="font-family:courier new">
							<?=$share['job_name']?>
						</div>
					</td>
					<td class="admin">
						<div style="font-family:courier new">
							<?=($share['admin'])?>
						</div>
					</td>
				</tr>
			<?endforeach;?>
				<form action="action.php" name="create_sharepoint" method="post">
				<tr class="content">
					<td class="link first-child">
						<input type="hidden" name="action" value="create_sharepoint" />
					</td>
					<td class="job_no">
						<div>
							<input type="text" name="job_no" value="WA-" style="text-transform:uppercase;border-bottom:1px solid lightgray;" />
						</div>
					</td>
					<td class="job_name">
						<div>
							<input type="text" name="job_name" value="" style="width:340px;" />
						</div>
					</td>
					<td class="admin">
						<div>
							<input type="text" name="admin" value="" style="width:200px;text-transform:lowercase;" />
						</div>
					</td>
				</tr>
				</form>
			</table>
		</div>
		<?php else: ?>
		<div class="view" id="list_view" style="padding:20px;">
			<p>
				There are noooo sharepoints
			</p>
		</div>

		<?php endif; ?>

		<script type="text/javascript">
		$(document).ready(function(){
			$('.list_view table tr th:first-child, .list_view table tr td:first-child').addClass('first-child');
			$('.list_view table tr th:last-child, .list_view table tr td:last-child').addClass('last-child');
			$('.list_view table tr.content:even').addClass('even');
			$('.list_view table tr.content:odd').addClass('odd');
		});
		</script>

	</div>
</div>

<button style="float:right;margin-right:25px;" onclick='document.forms["create_sharepoint"].submit()'>Create Sharepoint</button>

<?if(@$_SESSION['action_return']):?>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.action_return').delay(1500).fadeOut('slow');
		});
	</script>
	<div class="action_return">
		<?=$_SESSION['action_return']?>
	</div>

<?unset($_SESSION['action_return']);endif;?>

</body>
</html>