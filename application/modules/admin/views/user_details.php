<div class="content-wrapper">
   <section class="content-header">
      <h1>User Profile</h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
         <li><a href="<?php echo site_url('admin/user');?>"><i class="fa fa-user"></i>Users</a></li>
      </ol>
   </section>
   <section class="content ">
      <div class="row">
         <div class="col-md-3">
            <div class="box box-primary">
               <div class="box-body box-profile">
                  <?php 
                     if($dataexist->avatar){
                       $url = base_url().USER_AVATAR_THUMB.$dataexist->avatar; 
                      } else {
                        $url = base_url(). 'uploads/avatar/default.jpeg';
                      }
                     ?>
                  <img src="<?php echo $url;?> " class="profile-user-img img-responsive img-circle" alt="User Image">
                  <h3 class="profile-username text-center"><?php echo ucfirst(display_placeholder_text($dataexist->full_name)); ?></h3>
                  <p class="text-muted text-center"><?php echo display_placeholder_text($dataexist->email); ?></p>
                  <ul class="list-group list-group-unbordered">
                     <!-- <li class="list-group-item"> -->
                     <b>Received Gifts</b> <a class="pull-right"><?php echo empty($received->amount) ? '$0.00' : '$'.$received->amount; ?></a>
                     </li>
                     <br>
                     <!-- <li class="list-group-item"> -->
                     <b>Sent Gifts</b> <a class="pull-right"><?php echo empty($sent->amount) ? '$0.00 ' : '$'.$sent->amount; ?></a>
                     </li> 
                     <br>
                     <!-- <li class="list-group-item"> -->
                     <b>Swaps</b> <a class="pull-right"><?php echo $swap?></a>
                     </li>
                  </ul>
               </div>
            </div>
            <!-- About Me Box -->
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">About User</h3>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <strong><i class="fa fa-child"></i>Age</strong>
                  <p class="text-muted detail"><?php echo empty($dataexist->age) ? 'NA' : $dataexist->age; ?></p>
                  <hr>
                  <?php 
                     $gender = ($dataexist->gender == 1) ? "male" : "Female" ;
                     ?>
                  <strong><i class=" fa fa-transgender"></i> Gender</strong>
                  <p class="text-muted detail"><?php echo empty($dataexist->gender) ? 'NA' : $gender; ?></p>
                  <hr>
                  <strong><i class=" fa fa-car"></i> Car info</strong>
                  <p class="text-muted detail"><?php echo empty($car) ? 'NA' : $car->plate_number.'-'.' '.$car->model.' '.$car->make.' '.'('.$car->color.')'; ?></p>
                  <hr>
                  <?php 
                     $profile_language = ($dataexist->profile_language == 'es') ? "spanish" : "English" ;
                     ?>
                  <strong><i class=" fa fa-language"></i> Language</strong>
                  <p class="text-muted detail"><?php echo empty($dataexist->profile_language) ? 'NA' : $profile_language; ?></p>
               </div>
            </div>
            <!-- <div class="col-md-3">
               </div> -->
            <!-- /.nav-tabs-custom -->
         </div>
         <div class="col-md-9">
            <!-- <div class="nav-tabs-custom cr-report"> -->
            <div class="nav-tabs-custom">
               <ul class="nav nav-tabs box-blue" style="">
                  <li class="active text_head"><a href="#car" data-toggle="tab"><font color="black"><b>Swaps</b></font></a></li>
                  <li><a href="#account" data-toggle="tab"><font color="black"><b>Account</b></font></a></li>
                  <li><a href="#card" data-toggle="tab"><font color="black"><b>Card</b></font></a></li>
                  <li><a href="#sent" data-toggle="tab"><font color="black"><b>Sent Gifts</b></font></a></li>
                  <li><a href="#received" data-toggle="tab"><font color="black"><b>Received Gifts</b></font></a></li>
               </ul>
               <div class="tab-content">
                  <!-- car details -->
                  <div class="active tab-pane" id="car" data-userId="<?php echo $dataexist->userID; ?>">
                     <table id="user_details" class="table table-bordered table-striped" width="100%" data-id="<?php echo $dataexist->userID; ?>">
                        <thead>
                           <th style="width: 1px">Sno</th>
                           <th style="width: 110px">Looking Party</th>
                           <th style="width: 110px">Leaving Party</th>
                           <th style="width: 70px">From</th>
                           <th style="width: 70px">To</th>
                           <th>Gift</th>
                           <th>Date-Time</th>
                           <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                        </tfoot>
                     </table>
                  </div>
                  <!-- End -->
                  <!-- Account details-->
                  <div class="tab-pane" id="account">
                     <table id="account_details" class="table table-bordered table-striped" width="100%" data-id="<?php echo $dataexist->userID; ?>">
                        <thead>
                           <th style="width: 2%">Sno</th>
                           <th>Account holder Name</th>
                          
                           <th>Bank</th>
                           <th>Account Number</th>
                           <th>Routing Number</th>
                           <!--  <th>status</th>
                              <th style="width: 12%">Action</th>  -->
                        </thead>
                        <tbody>
                        </tbody>
                        </tfoot>
                     </table>
                  </div>
                  <!-- End -->
                  <!-- Card list -->
                  <div class="tab-pane" id="card" >
                     <table id="card_details" class="table table-bordered table-striped" width="100%" data-id="<?php echo $dataexist->userID; ?>">
                        <thead>
                           <th style="width: 2%">Sno</th>
                           <th>Card holder Name</th>
                           <th>Card last 4 digit</th>
                           <th>Card expiry month</th>
                           <th>Card expiry year</th>
                           <th>Card brand type</th>
                           <!--  <th>status</th>
                              <th style="width: 12%">Action</th>  -->
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
                  <div class="tab-pane" id="sent" >
                     <table id="sent_details" class="table table-bordered table-striped" width="100%" data-id="<?php echo $dataexist->userID; ?>">
                        <thead>
                           <th style="width: 2%">Sno</th>
                           <th>Sent to</th>
                           <th>Car details</th>
                           <th>Amount</th>
                           <th> Date-Time  </th>
                           <th style="width: 12%">Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
                   <div class="tab-pane" id="received" >
                     <table id="received_details" class="table table-bordered table-striped" width="100%" data-id="<?php echo $dataexist->userID; ?>">
                        <thead>
                           <th style="width: 2%">Sno</th>
                           <th>Sent by</th>
                           <th>Car details</th>
                           <th>Amount</th>
                           <th> Date-Time </th>
                           <th style="width: 12%">Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
         </div>
      </div>
      <!-- /.col -->
   </section>
</div>
<div id="form-modal-box"></div>