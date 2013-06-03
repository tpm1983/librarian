<?php

session_start();

require_once 'setup.php';
require_once 'includes/functions.php';


if(preg_match($_WS['HASH_REGEX'], @$_GET['hash'])) :

	$query = mysql_query("SELECT * FROM shares WHERE s_hash = '" . $_GET['hash'] . "'");
	if (mysql_num_rows($query)<1) exit('NO SHARE SELECTED OR FALSE ID');
	$share = mysql_fetch_assoc($query);
else :

	_ws_showerror('no sharepoint selected');
	exit;

endif;

if($auth) :

	$query= "SELECT * FROM files WHERE s_id = '" . $share['s_id'] . "' and status >= 0 ORDER BY status DESC, f_id DESC";
else :
	
	$query= "SELECT * FROM files WHERE s_id = '" . $share['s_id'] . "' and status = 1 ORDER BY f_id DESC";
endif;

$query = mysql_query($query);
while ($files = mysql_fetch_assoc($query)) {
	$array[] = $files;
}

$label = _ws_file_label();
$status = _ws_file_status();

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=0.9, maximum-scale=0.9">
	<title><?php echo $share['job_no'] . ' | ' . $share['job_name']; ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="uploadify-v3.1/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript">
	$(function() {
		$('#file_upload').uploadify({
			'swf'      : 'uploadify-v3.1/uploadify.swf',
			'uploader' : 'upload.php?id=<?php echo $share['s_id']; ?>',
			// Your options here
			'fileSizeLimit' : '2000MB',
			'buttonText' : 'Upload files',
			'onQueueComplete' : function() {
				alert( 'Thank You for Uploading!');
            	window.location.reload();
        	}
		});
	});
	</script>
</head>

<body>

<div id="base_logo"></div>
<form method="post" action="action.php">
<div id="base_wrapper">
	<div id="base_wrapper_browse">
		<?if($share['allow_upload']==1 || $auth):?>
		<input type="file" id="file_upload" />
		<?endif;?>

		<div id="project_info">
			<h1><?=$share['job_name']; ?></h1>
			<div id="project_info_no">
				<?=$share['job_no']; ?>
			</div>
			<?if(!empty($share['admin'])): ?>
			<div id="project_info_mail">
				Mail: <a href="mailto:<?=$share['admin']; ?>"><?=$share['admin']; ?></a>
			</div>
			<?endif;?>
		</div>

		<?if(!empty($array)):?>
		<div class="view" id="list_view">
			<table width="100%">
				<tr class="header">
					<th class="name">
						<div>
							<?if($auth):?><input type="checkbox" id="checkall"/>&nbsp;&nbsp;<?endif;?>
							Filename
						</div>
					</th>
					<th class="type">
						<div>
							Filetype
						</div>
					</th>
					<th class="size">
						<div>
							Size
						</div>
					</th>
					<th class="date">
						<div>
							Uploaded
						</div>
					</th>
				</tr>
				<?foreach($array as $key => $file):?>

				<?php $divi = ($file['size'] < 1048576) ? 1024 : 1048576; ?>
				<?$unit=($divi==1024)?'KB':'MB';?>
				<tr class="content label_<?php echo $label[$file['label']]; ?> status_<?php echo $status[$file['status']]; ?>">
					<td class="name first-child">
						<div>
							<?if($auth):?><input name="f_hash[]" class="file" type="checkbox" value="<?php echo $file['f_hash'] ?>" <?=(!$auth)?'checked="checked"':NULL;?>/>&nbsp;&nbsp;<?endif;?>
							<a href="/load:<?=$file['f_hash']?>" <?if($auth && $file['downloads'] > 0):?>title="<?=$file['downloads'];?> Downloads, <?=_ws_showtime($file['time_last_download'])?>"<?endif;?> target="_blank"><?php echo $file['name'] ?></a>
						</div>
					</td>
					<td class="type">
						<div>
							<?=strtoupper($file['extension'])?>
						</div>
					</td>
					<td class="size">
						<div>
							<?=round($file['size'] / $divi, 2) . ' ' . $unit?>
						</div>
					</td>
					<td class="date">
						<div>
							<?=_ws_showtime($file['time_create'])?>
						</div>
					</td>
				</tr>
			<?endforeach;?>
			</table>
		</div>
		<?php else: ?>
		<div class="view" id="list_view" style="padding:20px;">
			<p>
				This folder does not yet contain any files. Please use the <strong>Upload files-button</strong> to populate it!
			</p>
		</div>

		<?php endif; ?>

		<?if($auth):?>
		<div id="administration">
			Change files to:&nbsp;&nbsp;
			<select name="action">
				<option selected="selected"></option>
					<optgroup label="Status">
					<?php foreach ($status as $key => $value) : ?>
						<option value="status:<?php echo $key; ?>"><?php echo ucfirst($value); ?></option>
					<?php endforeach; ?>
					</optgroup>
					<optgroup label="Label">
					<?php foreach ($label as $key => $value) : ?>
						<option value="label:<?php echo $key; ?>"><?php echo ucfirst($value); ?></option>
					<?php endforeach; ?>
					</optgroup>
			</select>
			<input style="margin-left:25px;" type="checkbox" class="allow_upload" <?=($share['allow_upload']==1)?'checked="checked"':NULL;?>/>&nbsp;&nbsp;Allow Fileupload <i>(for ext. User)</i>
		</div>
		<?endif;?>

		<script type="text/javascript">
		$(document).ready(function(){
			$('.list_view table tr th:first-child, .list_view table tr td:first-child').addClass('first-child');
			$('.list_view table tr th:last-child, .list_view table tr td:last-child').addClass('last-child');
			$('.list_view table tr.content:even').addClass('even');
			$('.list_view table tr.content:odd').addClass('odd');

			$('#checkall').click(function(){

				t = $( this );

				if(t.attr('checked'))
					$('input.file').attr('checked', 'checked');

				else
					$('input.file').removeAttr('checked');

			});

			$('button').click(function(){

				var _action = $(this).attr('action');
				var _confirm = $(this).attr('confirm');

				if(confirm(_confirm)){
					self.location.href=_action;
				}
			});

			$('select[name="action"]').change(function(){

				this.form.submit();
			});

			$('input.allow_upload').click(function(){

				if($(this).attr('checked')){
					c= 1;
				}
				else {
					c= 0;
				}
					

				self.location.href="action.php?action=allow_upload:<?=$share['s_hash']?>:"+c;
			});
		});
		</script>

	</div>
</div>
</form>
</body>
</html>