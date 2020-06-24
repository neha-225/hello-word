<head>
   <link href="<?php echo base_url().ADMIN_PLUGIN; ?>toastr/toastr.min.css" rel="stylesheet">
</head>
<div class="container-fluid text-black" id="head1">
   <br>
  
   <p>stay healthy</p>
   <h1>Contact us</h1>
   <p style="color: black;">Far far awat, behind the word<br>mountains</p>
   <br> 
   
         <form id="contact_us" action="<?php echo base_url()?>home/contact_us" method="post">
          <div class="row">
              <div class="form-group">
              <label class="control-label col-sm-2" for="name"></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="first_name" placeholder="first_name" name="first_name" style="width: 140%; margin-left: 155%">
                </div>
              </div>
    
                 <div class="form-group">
              <label class="control-label col-sm-2" for="last_name"></label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="last_name" placeholder="last_name" name="last_name" style="width: 146%; margin-left: 190%">
                </div>
              </div>


          </div>
  
   
   <div class="form-group">
      <label class="control-label col-sm-2" for="email"></label>
      <div class="col-sm-10">
        <input type="email" class="form-control" id="email" placeholder="Email" name="email123" style="width: 63.5%; margin-left: 31.5%">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="subject"></label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" style="width: 63.5%; margin-left: 31.5%">
      </div>
    </div>

      <div class="form-group">
      <label class="control-label col-sm-2" for="email"></label>
      <div class="col-sm-10">
        <textarea class="form-control" rows="5" id="comment"name="message" placeholder=" Write your message here" style="width: 63.5%; margin-left: 31.5%"></textarea>
      </div>
      </div>
   
   <button type="submit" id="submit" class="btn btn-danger" style="margin-right: 26%;width: 20%;">Send message</button>
   </form>
   <br>
   <div id="box" style="margin-top: 0%; color: white; text-align: left; margin-left: 63%;">
      <br>
      <dt>&nbsp;&nbsp;&nbsp;&nbsp;Hours</dt>
      <dd>&nbsp;&nbsp;&nbsp;&nbsp;Opening:7:30am-closing:9:00pm &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button id="button"  type="button" class="btn btn-warning " style="color: white;">CONTACT US  <span class="fa fa-angle-right"></span></button></dd>
   </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"> </script>
<script src="<?php echo base_url().ADMIN_PLUGIN; ?>jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript"></script>
<script src="<?php echo base_url().FRONTEND_ASSETS; ?>plugins/toastr/toastr.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $("#contact_us").validate({
   ignore: [],
   rules:{
       
       first_name:{
           required: true,
           //email: true,
           maxlength: 100
       },
       last_name:{
           required: true,
           //email: true,
           maxlength: 100
       },
       subject:{
           required: true,
           maxlength: 100
       },
       email123:{
           required: true,
           maxlength: 100
       },
       message:{
           required: true,
           maxlength: 100
       }
       
   },
   
   });
   
   var login_admin = $("#contact_us");
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
         // $("#tl_admin_loader").show();
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
<style type="text/css">
   .error{
   color: red;
   /*text-align: center;*/
   }
</style>