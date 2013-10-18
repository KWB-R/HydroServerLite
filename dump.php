<?php
require_once 'internationalize.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<!--<title>Plupload - Form dump</title>-->
<title><?php echo $DumpTitle;?></title>
<style type="text/css">
	body {
		font-family:Verdana, Geneva, sans-serif;
		font-size:13px;
		color:#333;
		background:url(bg.jpg);
	}
</style>
</head>
<body>

<h1><?php echo $PostDump; ?></h1>

<p><?php echo $ShowItems; ?>.</p>

<table>
	<tr>
		<th><?php echo $Name; ?></th>
		<th><?php echo $Value; ?></th>
	</tr>
	<?php $count = 0; foreach ($_POST as $name => $value) { ?>
	<tr class="<?php echo $count % 2 == 0 ? 'alt' : ''; ?>">
		<td><?php echo htmlentities(stripslashes($name)) ?></td>
		<td><?php echo nl2br(htmlentities(stripslashes($value))) ?></td>
	</tr>
	<?php } ?>
</table>

</body>
</html>
