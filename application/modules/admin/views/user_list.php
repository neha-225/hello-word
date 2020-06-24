

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Users
         <?php
            if (isset($info)) {
              echo "(".$info.")";
            }
                  ?>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
         <li><a href="<?php echo site_url('admin/user');?>"><i class="fa fa-user"></i>Users</a></li>
         <!-- <li class="active"><?php echo $title ; ?></li> -->
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-xs-12">
            <!-- /.box -->
            <div class="box-header">
               <div class="row">
               </div>
            </div>
            <!-- /.box -->
            <div class="box">
               <!-- /.box-header -->
               <div class="box-body">
                  <table id="user_list" class="table table-bordered table-striped" width="100%">
                     <thead>
                        <th style="width: 7%">Sno</th>
                        <th style="width: 25%">User</th>
                        <!-- <th>Name</th> -->
                        <th>Email</th>
                        <th>Status</th>
                        <th style="width: 12%">Action</th>
                     </thead>
                     <tbody>
                     </tbody>
                     </tfoot>
                  </table>
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div id="form-modal-box"></div>

