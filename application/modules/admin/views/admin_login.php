

<body class="hold-transition login-page">
   <div class="login-box">
      <div class="login-logo">
         <a href="#"><img style="display:inline-block" width="360" src="<?php echo base_url() ?>backend_asset/img/logo.png" class="img-responsive" alt="" /></a>
         <!-- <a ><b>Admin</a> -->
      </div>
      <!-- /.login-logo -->
      <div class="card">
         <div class="card-body login-card-body">
            <form action="<?php echo site_url('admin/login') ?>" method="post" id="login_admin">
               <div class="form-group has-feedback">
                  <input name="email" value="" id="email" class="form-control" placeholder="Email Id" type="text">
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                     </div>
                  </div>
               </div>
               <div class="form-group has-feedback">
                  <input name="password"  id="password" class="form-control" placeholder="Password" type="password">
                  </style>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-4">
                  </div>
                  <!-- /.col -->
                  <div class="col-4">
                     <input type="button"  type="submit" class="btn btn-primary btn-block " id="submit" value="Sign In">
                  </div>
                  <!-- /.col -->
               </div>
            </form>
            <!-- /.social-auth-links -->
         </div>
         <!-- /.login-card-body -->
      </div>
   </div>
   <script type="text/javascript">
      $("#login_admin").validate({
      ignore: [],
      rules:{
          
          email:{
              required: true,
              email: true,
              maxlength: 100
          },
          password:{
              required: true,
              maxlength: 100
          }
          
      },
      
      });
      
      var login_admin = $("#login_admin");
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
                    toastr.error(data.msg);
               }  
            },
        });
                
      });
   </script>

