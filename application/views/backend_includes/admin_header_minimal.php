 <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="<?php echo base_url().ADMIN_ASSETS_IMG; ?>logo.jpg" type="image/gif" sizes="16x16">
   <title><?php echo ($title)? SITE_NAME.' | '.$title : SITE_NAME; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>/loader.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_BOWER; ?>font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_BOWER; ?>Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
   <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>AdminLTE.min.css"> 
   <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>aminlogin.min.css"> 


   <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>all.min.css"> 
   <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>style.css"> 
   <!-- <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>bootstrap.min.css">  -->
   <link rel="stylesheet" href="<?php echo base_url().ADMIN_PLUGIN; ?>fontawesome-free/css/all.min.css"> 
 
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  
  <!-- <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CUSTOM_CSS; ?>custom.css"> -->
  <link href="<?php echo base_url().ADMIN_PLUGIN; ?>toastr/toastr.min.css" rel="stylesheet"> 
  <script src="<?php echo base_url().ADMIN_BOWER; ?>jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url().ADMIN_PLUGIN; ?>jquery-validation/jquery.validate.min.js"></script>
  <!-- Google Font -->
 <!--  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>


  
  <!-- Left side column. contains the logo and sidebar -->
 