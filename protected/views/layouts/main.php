<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<title>GREEN</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css">
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php include('estilos.php');?>
</head>

<body>
<?php 
$active='inicio';
include('menu.php');?>
<ol class="breadcrumb">
  <li class="active"><a href="#">Dashboard</a></li>
</ol>

		
<?php echo $content; ?>
<div id="myModalUploadExcel" class="modal fade" style="display: none;" aria-hidden="true">
</div>
<div id="myModalEditField" class="modal fade" style="display: none;" aria-hidden="true">
</div>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
