<head>
   <link href="<?php echo base_url().ADMIN_PLUGIN; ?>toastr/toastr.min.css" rel="stylesheet">
</head>
<div class="cart-table-area section-padding-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="checkout_details_area mt-50 clearfix">

                            <div class="cart-title">
                                <h2>Checkout</h2>
                            </div>
   
         <form id="contact_us" action="<?php echo base_url()?>home/checkoutInsert" method="post">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="" placeholder="First Name" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="" placeholder="Last Name" >
                                    </div>
                                   <!--  <div class="col-12 mb-3">
                                        <input type="text" class="form-control" id="company" placeholder="Company Name" value="">
                                    </div> -->
                                    <div class="col-12 mb-3">
                                        <input type="email" class="form-control" id="email"  name="email" placeholder="Email" value="">
                                    </div>
                                    <!-- <div class="col-12 mb-3">
                                        <select class="w-100" id="country">
                                        <option value="usa">United States</option>
                                        <option value="uk">United Kingdom</option>
                                        <option value="ger">Germany</option>
                                        <option value="fra">France</option>
                                        <option value="ind">India</option>
                                        <option value="aus">Australia</option>
                                        <option value="bra">Brazil</option>
                                        <option value="cana">Canada</option>
                                    </select>
                                    </div> -->
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control mb-3" id="address" name="address" placeholder="Address" value="">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control" id="city" name="city" placeholder="Town" value="">
                                    </div>
                                   <!--  <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="zipCode" placeholder="Zip Code" value="">
                                    </div> -->
                                    <div class="col-md-12 mb-3">
                                        <input type="number" class="form-control" id="phone_number" name="phone"  placeholder="Phone No" value="">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <textarea name="comment" class="form-control w-100" id="comment" cols="30" rows="10" placeholder="Leave a comment about your order"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <!-- <div class="custom-control custom-checkbox d-block mb-2">
                                            <input type="checkbox" class="custom-control-input" id="customCheck2">
                                            <label class="custom-control-label" for="customCheck2">Create an accout</label>
                                        </div>
                                         --><!-- <div class="custom-control custom-checkbox d-block">
                                            <input type="checkbox" class="custom-control-input" id="customCheck3">
                                            <label class="custom-control-label" for="customCheck3">Ship to a different address</label>
                                        </div>
                                         --> <div id="submit" name="submit"  class="cart-btn mt-100">
                                <a href="#" class="btn amado-btn w-100">Continue to checkout</a>
                            </div>
                            <!-- <button type="submit" id="submit" name="submit">checkout</button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
         
                </div>
            </div>
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
         
           maxlength: 100
       },
       last_name:{
           required: true,
           maxlength: 100
       },
       phone:{
           required: true,
           maxlength: 100
       },
       email:{
           required: true,
           maxlength: 100
       },
       address:{
           required: true,
           maxlength: 100
       },
       city:{
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