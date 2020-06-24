
  <!DOCTYPE html>
<html>
<head>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url().BACKEND_ASSET;?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" >
   <title><?php echo ($title)? SITE_NAME.' | '.$title : SITE_NAME; ?></title>
    <link rel="icon" href="<?php echo base_url().ADMIN_ASSETS_IMG; ?>logo.jpg" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_BOWER; ?>bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_BOWER; ?>font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_BOWER; ?>Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>AdminLTE.min.css">
  
  <link href="<?php echo base_url().BACKEND_ASSET; ?>custom/css/admin_custom.css" rel="stylesheet" type="text/css">      

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>skins/skin-red.css">

  <link href="<?php echo base_url().ADMIN_PLUGIN; ?>toastr/toastr.min.css" rel="stylesheet"> 
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_PLUGIN; ?>datatables/dataTables.bootstrap.css">
  <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>

  <script src="<?php echo base_url().ADMIN_BOWER; ?>jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url().ADMIN_PLUGIN; ?>jquery-validation/jquery.validate.min.js"></script>
  <script src="<?php echo base_url().ADMIN_PLUGIN; ?>bootbox/bootbox.min.js"></script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="<?php echo base_url().ADMIN_ASSETS_CSS; ?>/loader.css">

</head>
<body class="hold-transition skin-red sidebar-mini" id="tl_admin_main_body" data-base-url="<?php echo base_url(); ?>">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?php echo base_url().ADMIN_ASSETS_IMG; ?>logo.jpg" type="image/gif" sizes="16x16"></span>
      <!-- logo for regular state and mobile devices -->
      <?php 
         $fname = $_SESSION[ADMIN_USER_SESS_KEY]['name'];
         ?>

        <span><img width="170" src="<?php echo base_url().ADMIN_ASSETS_IMG; ?>logo1.png" type="image/gif" sizes="16x16"><b style="color: white;"></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
         
          <!-- Tasks: style can be found in dropdown.less -->
         
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php 

                 if($_SESSION[ADMIN_USER_SESS_KEY]['avatar']){
                   $url = base_url().ADMIN_AVATAR.$_SESSION[ADMIN_USER_SESS_KEY]['avatar']; 
                  } 
                  else {
                    $url = base_url(). 'uploads/avatar/default.jpeg';
                  }
                 ?>
                     <img src=" <?php echo $url;?>" class="user-image" alt="User Image">

             <!--  <img src="<?php echo base_url().ADMIN_ASSETS_IMG; ?>user2-160x160.jpg" class="user-image" alt="User Image"> -->
              <span class="hidden-xs" style="color: black;"><?php echo $fname;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
               <?php 
                 if($_SESSION[ADMIN_USER_SESS_KEY]['avatar']){
                   $url = base_url().ADMIN_AVATAR.$_SESSION[ADMIN_USER_SESS_KEY]['avatar']; 
                  } else {
                    $url = base_url(). 'uploads/avatar/default.jpeg';
                  }
                 ?>
                     <img src=" <?php echo $url;?>" class="img-circle" alt="User Image">
                <!-- <img src="<?php echo base_url().ADMIN_ASSETS_IMG; ?>
                user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                <div style="color: black; margin-top: 30px;"><?php echo $_SESSION[ADMIN_USER_SESS_KEY]['emailId']?></div>

              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url(); ?>admin/admin_profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url()?>/admin/logout" class="btn btn-default btn-flat">Log  out</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      
      <!-- sidebar menu: : style can be found in sidebar.less -->

      <ul class="sidebar-menu" data-widget="tree">

        <li class="<?php echo (strtolower($this->router->fetch_class()) == "admin") ? "active" : "" ?>">
            <a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
        </li>
        <li class="<?php echo (strtolower($this->router->fetch_class()) == "form") ? "active" : "" ?>"><a href="<?php echo base_url(); ?>admin/form"><i class="fa fa-user"></i><span>Add Product</span></a></li>
       <!--  <li class="<?php echo (strtolower($this->router->fetch_class()) == "classctrl") ? "active" : "" ?>"><a href="<?php echo base_url(); ?>admin/classctrl"><i class="fa fa-user"></i><span>Class</span></a></li>
        <li class="<?php echo (strtolower($this->router->fetch_class()) == "ourctrl") ? "active" : "" ?>"><a href="<?php echo base_url(); ?>admin/ourctrl"><i class="fa fa-user"></i><span>Ours trainers</span></a></li>
         -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>