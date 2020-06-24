

<!--- Content Wrapper. Contains page content -->
<style type="text/css">
   .info-box-icon i{
   padding-top: 20px;
   }
</style>
<?php $backend_assets =  base_url().'backend_asset/';
   ?>
  
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Profile
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">User profile</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box box-primary">
               <div class="box-body box-profile">

                 <?php 
                 if($_SESSION[ADMIN_USER_SESS_KEY]['avatar']){
                   $url = base_url().ADMIN_AVATAR.$_SESSION[ADMIN_USER_SESS_KEY]['avatar']; 
                  } else {
                    $url = base_url(). 'uploads/avatar/default.jpeg';
                  }
                 ?>
                  <img src="<?php echo $url;?> " class="profile-user-img img-responsive img-circle" alt="User Image">
                  <h3 class="profile-username text-center"><?php echo $_SESSION[ADMIN_USER_SESS_KEY]['name'];?></h3>
                  
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
         <div class="col-md-9">
            <div class="nav-tabs-custom">
               <ul class="nav nav-tabs">
                  <li class="active"><a href="#activity" data-toggle="tab">update profile</a></li>
                  <!--    <li><a href="#timeline" data-toggle="tab">change password</a></li> -->
                  <li><a href="#settings" data-toggle="tab">change password</a></li>
               </ul>
               <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                     <div class="post">
                        <div class='user-block'>
                           <form action="<?php echo site_url(); ?>admin/admin_update" method="POST" class="form-horizontal"  id ="update" enctype="multipart/form-data">
                           <div class="form-group">
                                <input type="hidden" value="<?php echo $_SESSION[ADMIN_USER_SESS_KEY]['adminUserID'];?>"   name ="adminUserID" id="adminUserID" placeholder=""> 
                                <input type="hidden" value="<?php echo $_SESSION[ADMIN_USER_SESS_KEY]['avatar'];?>"   name ="exit_image" id="exit_image" placeholder="">      
                                 <label for="inputName" class="col-sm-2 control-label">Name</label>
                                 <div class="col-sm-6">
                                    <input type="text"  value="<?php echo $_SESSION[ADMIN_USER_SESS_KEY]['name'];?>" class="form-control" name ="name" id="name" placeholder="">  
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="inputName" class="col-sm-2 control-label">email</label>
                                 <div class="col-sm-6">
                                    <input type="text" value="<?php echo $_SESSION[ADMIN_USER_SESS_KEY]['emailId'];?>" readonly class="form-control" name ="email" id="email" placeholder=""> 
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">image </label>
                                  <div class="col-sm-6">

                                    <input type="file"  name="avatar" id="avatar">
                                  </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-offset-2 col-sm-10">
                                    <button type ="submit" id="submit" class="btn btn-default btn-flat">update</button>
                                 </div>
                              </div>
                           </form>
                           <span class='username'>
                        </div>
                        <!-- /.user-block -->
                        <div class='row margin-bottom'>
                           <div class='col-sm-6'>
                           </div>
                           <div class='col-sm-6'>
                              <div class='row'>
                                 <div class='col-sm-6'>
                                 </div>
                                 <!-- /.col -->
                                 <div class='col-sm-6'>
                                 </div>
                                 <!-- /.col -->
                              </div>
                              <!-- /.row -->
                           </div>
                           <!-- /.col -->
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="settings">
                     <form  action="<?php echo site_url(); ?>admin/changePassword" id="change" class="form-horizontal" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                           <label for="inputName" class="col-sm-2 control-label">old password</label>
                           <div class="col-sm-6">
                              <input type="password"  name="password" class="form-control" id="password" placeholder="">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="inputEmail" class="col-sm-2 control-label">New password</label>
                           <div class="col-sm-6">
                              <input type="password"  name="npassword" class="form-control" id="npassword" placeholder="">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="password" class="col-sm-2 control-label">confirm password</label>
                           <div class="col-sm-6">
                              <input type="password"  name="rnpassword" class="form-control" id="rnpassword" placeholder="">
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="col-sm-offset-2 col-sm-10">
                              <button type="submit" id="sub" name="sub" class="btn btn-danger" >Submit</button>
                           </div>
                        </div>
                     </form>
                  </div>
                  <!-- /.tab-pane -->
               </div>
               <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
<script type="text/javascript">

  // $("#change").validate({
  // ignore: [],
  // rules:{

  //     password:{
  //         required: true,
  //         maxlength: 100
  //     },
  //     npassword:{
  //         required: true,
  //         maxlength: 100
  //     },
  //     rnpassword:{
  //         required: true,
  //         maxlength: 100
  //     }
      
  // },

  // });

  var login = $("#change");
  var proceed_err  = 'Please fill all the fields properly';
    $('body').on('click','#sub', function(){
    toastr.remove();
    event.preventDefault();
    if(login.valid()===false){
        // $('#tl_admin_loader').show();
        toastr.error(proceed_err);
        return false;
    }

    var _that = $(this), 
    form = _that.closest('form'),      
    formData = new FormData(form[0]),
    f_action = form.attr('action');
    
      $.ajax({
        type: "POST",
        url: f_action,
        data: formData, //only input
        processData: false,
        contentType: false,
        // dataType: "JSON", 
         beforeSend: function () { 
     $("#tl_admin_loader").show();
      },
        success: function (data, textStatus, jqXHR){ 
            var data = $.parseJSON(data);
            if (data.status == 1){ 
              $('#tl_admin_loader').show();
              toastr.success(data.msg);
                window.setTimeout(function (){
      window.location.href =data.url;
    }, 1000);
            }
            else {
                   $("#tl_admin_loader").hide();

                toastr.error(data.msg);
                  
            }  
        },
    });
            
  });

    $("#update").validate({
      ignore: [],
      rules:{
          
          email:{
              required: true,
              email: true,
              maxlength: 100
          },
          name:{
              required: true,
              maxlength: 100
          }
          
      },

    });

  var login_admin = $("#update");
  var proceed_err  = 'Please fill all the fields properly';
    $('body').on('click','#submit', function(){

  toastr.remove();
  event.preventDefault();
    if(login_admin.valid()===false){
        toastr.error(proceed_err);
        return false;
    }
    var _that = $(this), 
    form = _that.closest('form'),      
    formData = new FormData(form[0]),
    f_action = form.attr('action');
    
      $.ajax({
        type: "POST",
        url: f_action,
        data: formData, //only input
        processData: false,
        contentType: false,
        dataType: "JSON", 
        beforeSend: function () { 
     $("#tl_admin_loader").show();
      },
        success: function (data, textStatus, jqXHR){ 
            if (data.status == 1){ 
              $('#tl_admin_loader').show();
              toastr.success(data.msg);
                window.setTimeout(function (){
      window.location.href =data.url;
    }, 1000);
            }
            else {
              $("#tl_admin_loader").hide();

                toastr.error(data.msg);
                  
            }  
        },
    });
            
  });
</script>

<!-- <script type="text/javascript">

</script> -->
<style type="text/css">
  .error{
    color: red;
  }
</style>


<!-- var _that = $(this), 
form = _that.closest('form'), 
formData = new FormData(form[0]),
f_action = form.attr('action');

$.ajax({
type: "POST",
url: f_action,
data: formData, //only input
processData: false,
contentType: false,
dataType: "JSON", 
beforeSend: function () { 
$("#tl_admin_loader").show();
},
success: function (data, textStatus, jqXHR){ 
// alert("jjj");
if (data.status == 1){ 
toastr.success(data.msg);
window.setTimeout(function (){
$("#tl_admin_loader").show();
window.location.href =data.url;
}, 1000);
}
else {
$("#tl_admin_loader").hide();
toastr.error(data.msg);

} 
},
}); -->