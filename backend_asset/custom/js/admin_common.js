var base_url = $('#tl_admin_main_body').attr('data-base-url');

function show_loader(){
    $('#tl_admin_loader').show();
}

function hide_loader(){
    $('#tl_admin_loader').hide();
}

/** start script in application **/
var logout = function () {
    bootbox.confirm('Are you sure want to logout?', function (isTrue) {
        if (isTrue) {
            $.ajax({
                url: base_url+'admin/logout',
                type: 'POST',
                dataType: "JSON",
                success: function (data) {
                    window.location.href = base_url+"admin/";
                }
            });
        }
    });
}

/** backend script **/


    var addFormBoot = function (ctrl, method)
    {
        $(document).on('submit', "#add-form-common", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('show');
                            toastr.success(data.message);


                            window.setTimeout(function () {
                                window.location.href = "<?php echo base_url(); ?>" + ctrl;
                            }, 2000);
                            

                        } else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader()
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var updateFormBoot = function (ctrl, method)
    {
        $("#edit-form-common").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('hide');
                            toastr.success(data.message);
                            window.setTimeout(function () {
                                window.location.href = base_url + ctrl;
                            }, 2000);
                            

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var editFn = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader()
            }
        });
    }


    var showFn = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id}, 
            beforeSend: function () { 
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader()
            }
        });
    }



    /**** for updating admin profile ****/
    var base_url = $('#tl_admin_main_body').attr('data-base-url');
    $('body').on('click', ".update_admin_profile", function (event) { 
        var _that = $(this), 
            form = _that.closest('form'),      
            formData = new FormData(form[0]),
            f_action = form.attr('action');  
            
    //console.log(formData+'-----'+f_action);
        $.ajax({
            type: "POST",
            url: f_action,
            data: formData, //only input
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function () {
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(3000);
                                 window.location.href = data.url;
                        }, 3000);
                    } 
            },
            error:function (){
                
            }
        });

    });


      /**** forgot password ****/
    var base_url = $('#tl_admin_main').attr('data-base-url');
    $('body').on('click', ".forgot_password", function (event) {  alert('k');
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
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    } 
            },
            error:function (){
                
            }
        });

    });


     /**** for updating about_us content ****/
    var base_url = $('#tl_admin_main_body').attr('data-base-url');
    $('body').on('click', ".update_content", function (event) { 
        var _that = $(this), 
            form = _that.closest('form'),      
            formData = new FormData(form[0]),
            f_action = form.attr('action'); 
            
    //console.log(formData+'-----'+f_action);
        $.ajax({
            type: "POST",
            url: f_action,
            data: formData, //only input
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function () {
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    } 
            },
            error:function (){
                
            }
        });

    });


    /**** for changing admin password ****/
    var base_url = $('#tl_admin_main_body').attr('data-base-url');
    $('body').on('click', ".change_password", function (event) { 
        var _that = $(this), 
            form = _that.closest('form'),      
            formData = new FormData(form[0]),
            f_action = form.attr('action');  
            
    //console.log(formData+'-----'+f_action);
        $.ajax({
            type: "POST",
            url: f_action,
            data: formData, //only input
            processData: false,
            contentType: false,
            dataType: "JSON",
            beforeSend: function () {
              show_loader()
            },
            success: function (data, textStatus, jqXHR) {
                    hide_loader()
                    if (data.status == 1){

                        toastr.success(data.message);
                            window.setTimeout(function () {
                                 window.location.href = data.url;
                            }, 2000);
                        $(".loaders").fadeOut("slow");

                    } else {

                        toastr.error(data.message);
                        //toastr.options.positionClass = 'toast-top-center'
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        $(".loaders").fadeOut("slow");
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    } 
            },
            error:function (){
                
            }
        });

    });


    var viewFn = function (ctrl, method, id,isReceived="0") {
         //alert(isReceived);

        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id , 'received' :isReceived},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader()
            }
        });
    }

    var open_modal = function (controller) {
        var userType=$('.userTypeGet').data('user-type');
        $.ajax({
            url: base_url + controller + "/open_model",
            type: 'POST',
            data:{'userType':userType},
            success: function (data, textStatus, jqXHR) {
                console.log(data);

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');

            }
        });
    }
    var open_modal_plan = function (controller) {   

            var userType=$('.userTypeGet').data('user-type');   
            $.ajax({
                url: base_url + "admin/planctrl/open_modal_plan", 
                type: 'GET',
                data:{'userType':userType},
                success: function (data, textStatus, jqXHR) {
                    $('#form-modal-box').html(data);
                    $("#addPlan").modal('show');
                }
            });
        }

    var editplanDetail = function (ctrl, id) {
        $.ajax({
            url: base_url + ctrl + 'admin/planctrl/edit_plan_detail',
            type: 'POST',
            data: {'id': id},
          beforeSend: function () {
                show_loader()
               },
          success: function (data, textStatus, jqXHR) {
            hide_loader();
            $('#form-modal-box').html(data);
            $("#editPlan").modal('show');   
            }
        });
    }   

    var deleteFn = function (table, field, id, ctrl, method) {
        
        bootbox.confirm({
            message: "Are you sure you want to delete this record ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn btn-warning'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    show_loader();
                    var url = base_url+ctrl+'/'+method;
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        data: {id: id, id_name: field, table: table},
                        success: function (data) {
                            hide_loader();
                            if (data.status == 1) {
                                toastr.success(data.message);
                                window.setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            }
                            else{
                                toastr.error(data.message);
                            }
                        },
                        error: function (error, ror, r) {
                            toastr.error(error);
                        },
                    });
                }
            }
        });

    }


    var statusFn = function (ctrl, method, id, status, table, field,dataitem) {
        // console.log(ctrl);

        var message = "";
        if (status == '1') {
            message = "inactive";
          
            tosMsg = 'Successfully inactivated';
        } else if (status == '0') {
            message = "active";
           
            tosMsg = 'Successfully activated';
        }

        bootbox.confirm({
            message: "Are you sure, you want to " + message + " this "+dataitem+" ?",
            buttons: {
                confirm: {
                    label: 'Ok',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    show_loader();
                    var url = base_url+ctrl+'/'+method;
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table, status: status},
                        success: function (response) {
                            var data = $.parseJSON(response);
                            hide_loader();
                            if (data.status == 1) {
                        
                                toastr.success(tosMsg);
                                window.setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                              
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
            }
        });
    }

$(document).ready(function () {
   var base_url = $('#tl_admin_main_body').attr('data-base-url'); 
  toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        timeOut: 2000,
        "fadeIn": 300,
    };
    
    $(document).on('submit', "#addFormAjax", function (event) {
        event.preventDefault();
        var _that = $(this),
        formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: _that.attr('action'),
            data: formData, //only input
            processData: false,
            contentType: false,
            beforeSend: function () {
                show_loader()
            },
            success: function (response, textStatus, jqXHR) {
                try {
                    var data = $.parseJSON(response);
                    if (data.status == 1)
                    {
                        $("#commonModal").modal('hide');
                        toastr.success(data.message);
                        if(data.url != ""){
                        window.setTimeout(function () {
                            window.location.href = data.url;
                        }, 2000);
                       }
                        hide_loader()

                    } else {
                        toastr.error(data.message);
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader()
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                } catch (e) {
                    //$('#error-box').show();
                    //$("#error-box").html(data.message);
                    console.log(data.message);
                    hide_loader()
                    setTimeout(function () {
                        $('#error-box').hide(800);
                    }, 1000);
                }
            }
        });

    });


/****for category list****/
var base_url = $('#tl_admin_main_body').attr('data-base-url');

$('#status').on('change', function(){  
    var catId = $(this).val();  
        $.ajax({
            type: "POST",
            url:  base_url+"admin/category/singleCategory",
            data : {catId : catId}

        }).done(function(response) {
            //console.log(response); 
            //$('#cat_list').append(response);
            $('#cat_list').html(response);

        });
});


    $(document).on('submit', "#editFormAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                 beforeSend: function () {
                    show_loader();
                 },
                success: function (response, textStatus, jqXHR) {
                    hide_loader();
                    try {
                        
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            $("#commonModal").modal('hide');
                            toastr.success(data.message);
                            
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                            
                        }else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
});

jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
            console.log(split_extension+'---'+file_name.split("."));
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg, jpeg, png');
            return false;
        }
        
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }

        $('.edit_img').addClass("imUpload");
    });

    jQuery('body').on('change', '.input_img3', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg,jpeg,png');
            return false;
        }
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });
    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#user_image").val("");

        $('.edit_img').removeClass("imUpload");
    });

var dataTable = $('#common_datatable_users');    
if(dataTable.length !== 0){
    $('#common_datatable_users').dataTable({
        /*columnDefs: [{orderable: false, targets: [4, 6, 7]}]*/
        "pageLength": 10
    });
}

// Upload File of Terms & Condition
$(document).ready(function(){
   $('#tc_upload').change(function(event){
     var type_check = event.target.files[0].type;
      if(type_check != 'application/pdf'){
        toastr.error('Only PDF file allowed');
            $('#error-box').show();
            $("#error-box").html('Only PDF file allowed');
            
         setTimeout(function () {
            $('#error-box').hide(800);
        }, 1000);
         setTimeout(location.reload.bind(location), 2800);    
      }
      $('#tc_file_name').text(event.target.files[0].name);
   });
});

//common class for onkeypress validatenumber call
$('body').on('keypress','.number_only',validateNumbers);
/*To validate number only*/
function validateNumbers(event) {
  if (event.keyCode == 46){
    return false;
  }
  var key = window.event ? event.keyCode : event.which;
  if (event.keyCode == 9 || event.keyCode == 8 || event.keyCode == 46) {
      return true; //allow only number key and period key
  }
  else if ( (key < 48 || key > 57) && key != 190 ) {
      return false;
  }
  else return true;
};

$(function () {
    no_record_msg = '<h4 class="text-danger">No record found</h4>'; 
    //$("#example1").DataTable();
    $('#example2, #example1').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,    
      "blengthChange": false,
      "columnDefs": [
   { orderable: false, targets: -1 },
   { orderable: false, targets: -2 }
]
    });
    
    //datatables initailize

    //users
   
    table = $('#user_list').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "lengthChange": false,
        "order": [], //Initial no order.
        "iDisplayLength" :10,
        "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/user/user_list_ajax",
            "type": "POST",
            "dataType": "json",
            // data:function(d) {
            //     d['user_id'] = table.attr('data-id');
            //     },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            { orderable: false, targets: -2 }
        ]

    });

    

     


   var g = $('#addplan').attr('data-id');
   var details = $('#addplan').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "lengthChange": false,
        "order": [], //Initial no order.
        "iDisplayLength" :10,
        "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/planctrl/add_plan_details_ajax",
            "type": "POST",
            "dataType": "json",
            'data':{
                subscriptionPlanID :g
                },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            { orderable: false, targets: -2 }
        ]

    });




    var g = $('#user_details').attr('data-id');
    var details = $('#user_details').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "lengthChange": false,
        "order": [], //Initial no order.
        "iDisplayLength" :10,
        "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/user/user_details_ajax",
            "type": "POST",
            "dataType": "json",
            'data':{
                user_id :g
                },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            { orderable: false, targets: -2 }
        ]

    });

   
    

    var g = $('#card_details').attr('data-id');
    var details = $('#card_details').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "lengthChange": false,
        "order": [], //Initial no order.
        // "oLanguage": {
        // "sEmptyTable" : '<center>No record found</center>',
        // },
        // "oLanguage": {
        // "sZeroRecords" : '<center>No record found</center>',
        // },
        "iDisplayLength" :10,
        "language": {                
         "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/cardctrl/card_details_ajax",
            "type": "POST",
            "dataType": "json",
            'data':{
                user_id :g
                },
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            { orderable: false, targets: -2 }
        ]

    });

    

  
    

    var date_inp = $("#datepicker");
    date_inp.datepicker({
        autoclose: true,
        keepOpen: false,
        todayHighlight: true,
        useCurrent: false,
    });

    date_inp.datepicker().on('changeDate', function (ev) {
        table_post.draw();
    });_
    
    $('#clearDate').on('click', function () {
        var d = new Date();
        date_inp.datepicker('update',d);
        date_inp.datepicker('update','');
        table_post.draw();
    });
    
     // Stop Keyboard Wroking   
    $('#datepicker').keydown(function(event) { 
        return false;
    });

    jQuery('body').on('click', '.remove_img1', function () {
         var img = jQuery(this).data('avtar');
         jQuery('.ceo_logo img').attr('src', img);
         jQuery(this).css("display", "none");
         jQuery('#check_delete').val('1');
    });

});





