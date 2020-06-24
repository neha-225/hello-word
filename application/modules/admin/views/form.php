<body class="hold-transition skin-blue sidebar-mini">
   <div class="wrapper">
      <!-- Left side column. contains the logo and sidebar -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
         <section class="content">
            <div class="row">
               <div class="col-md-9">
                  <div class="nav-tabs-custom">
                     <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab">Add Product</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="active tab-pane" id="activity">
                           <div class="tab-pane" id="settings">
                              <form id="add_image"class="form-horizontal" enctype="multipart/form-data"action="<?php echo base_url('admin/form/form1')?>"method="POST">
                                 <div class="form-group">
                                    <div class="col-sm-10">
                                       <input type="hidden" class="form-control" id="Id" placeholder="Id" name="Id">
                                       <input type="hidden" value=""   name ="exit_image" id="exit_image" placeholder="">  
                                    </div>
                                 </div>
                                 
                                 <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">product</label>
                                    <div class="col-sm-10">
                                       <input type="textStatus" class="form-control" name="product" id="product">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="model" class="col-sm-2 control-label">Model</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="model" id="model" class="form-control" placeholder=""> 
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">price</label>
                                    <div class="col-sm-10">
                                       <input type="text"  value="" class="form-control" name ="price" id="price" placeholder="">  
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                       <input type="text"  value="" class="form-control" name ="description" id="description" placeholder="">  
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-10">
                                       <input type="file"  value="" class="" name ="image" id="image" placeholder="">  
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                       <button id="submit" name="submit" type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                           <!-- Post -->
                        </div>
                     </div>
                  </div>
               </div>
               <!-- /.col -->
            </div>
            <!-- /.row -->
         </section>
         <!-- /.content -->
      </div>
     
  
</body>
</html>
<script type="text/javascript">
   $("#add_image").validate({
   ignore: [],
   rules:{
   
      
       product:{
           required: true,
           maxlength: 100
        },
        model:{
           required: true,
           maxlength: 100
       },
      price:{
           required: true,
           maxlength: 100
       },
       description:{
           required: true,
           maxlength: 100
       },
       //  image:{
       //     required: true,
       //     maxlength: 100
       // },
   },
   
   });
   
   var loginasd = $("#add_image");
   var proceed_err  = 'Please fill all the fields properly';
     $('body').on('click','#submit', function(){
     toastr.remove();
     event.preventDefault();
     if(loginasd.valid()===false){
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
    
</script>
<style type="text/css">
   .error{
   color: red;
   }
</style>
</div>
<script type="text/javascript">
   $( "select[name='category']" ).change(function () {
     var id = $(this).val();
   if(id) {
   $.ajax({
   url: "<?php echo base_url('product/product/getAllWhere');?>",
   type: "POST",
     dataType: 'Json',
       data: {'id':id},
         success: function(data) {
           $('select[name="subcategory"]').empty();
             $.each(data, function(key, value) {
                 $('select[name="subcategory"]').append('<option value="'+ value.id +'">'+ value.subcategory +'</option>');
                 });
               }
           });
         }
   else{
     $('select[name="subcategory"]').empty();
       }
    });
</script>