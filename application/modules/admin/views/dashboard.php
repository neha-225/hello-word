

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Dashboard
        
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url();?>"><i class="fa fa-dashboard"></i>Home</a></li>
        
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
         
         <!-- ./col -->
        
         <!-- ./col -->
         <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
               <div class="inner">
                  <h3> <?php
                     if (isset($info)) {
                     echo $info;
                     }
                     ?></h3>
                  <p>Users</p>
               </div>
               <div class="icon">
                  <i class="ion ion-person"></i>
               </div>
               <a href="user" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <!-- ./col -->
         
      </div>
      <!-- /.row -->
      <!-- /.row (main row) -->
   </section>
   <!-- /.content -->
</div>

