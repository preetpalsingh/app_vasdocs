
$(function () {

    
    $('#img_url').on('change', function () {
        var fileReader = new FileReader();
        fileReader.onload = function () {
            $('#img_val').val(fileReader.result); // data <-- in this var you have the file data in Base64 format
        };
        fileReader.readAsDataURL($('#img_url').prop('files')[0]);
    });

    $(document).on("click",".modal-delete-trigger",function(event) {	

        event.stopPropagation();
        delete_id = $(this).attr('id');
        
        images = $(this).data("images");

        swal({
            title: "Delete Permanently",
            text: "Are you Sure You wanted to Delete?",
            icon: "warning",
            buttons: {
                cancel : 'No, cancel please!',
                confirm : {text:'Yes, delete it!',className:'sweet-warning',
            closeModal: false,}
            },
            dangerMode: true,
        })
        .then((willDelete) => {

            if (willDelete) {

                $(".sa-confirm-button-container .confirm").prop('disabled',true);

                var url = base_url+'/admin/order-delete';

                var FormData = {
                    delete_id: delete_id
                    };

               // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

                datastring = 'delete_id='+delete_id;
                $.ajax({
                    url:url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    data:datastring,
                    
                    beforeSend: function()
                    {
                        $(".sa-confirm-button-container .confirm").prop('disabled',true);
                        
                        $('.sa-confirm-button-container .confirm').html('<center><i class="fa fa-refresh fa-spin  fa-fw"></i> Wait...</center>');
                        
                    },
                    
                    success: function(data)   
                    {  
                
                        //var obs = $.parseJSON(data);
                        var obs = data;
                    
                        if(obs['status']== true)
                        {  
                            swal("Deleted!", 'Record Delete Successfully !', "success");
                            setTimeout(function(){location.reload();},3000);
                                
                        }
                        else{
                            
                            swal("Cancelled", 'Technical Error !', "error");
                        }
                        
                    },

                    error: function(data, textStatus, errorThrown)
                    {
                        if(data.status == '403'){

                            swal("Cancelled", 'You does not have the right permissions.', "error");
                        }
                    }
                });
                
                return false;
                
            } else {

                swal("Cancelled", "This process has been cancelled :)", "error");

            }
        });
    });

    var editFeedId = 0;
    

    $(document).on("click", ".update_order_status", function () {
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('Edit '+modal_title);
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');

        $('#edit_id').val($(this).data('id'));
        $('#status').val($(this).data('status'));
    });

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
		
        var url             = base_url+'/admin/update-order-status';
        var status           = $('#status').val();
        var edit_id         = $('#edit_id').val();

        var data = {
            status: status,
            edit_id: edit_id,
        };
        
            axios.post(url, data).then(function (response) {
                
                if (response.data.status == true) {

                    $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

                    setTimeout(function(){location.reload();},2500);

                } else {
                    
					$('.msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
                }

                $('.sp_ajax_loader').remove();

            }).catch(function (error) {

                if (error == 'Error: Request failed with status code 403') {

                    $('.msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
                }
				
				$('.sp_ajax_loader').remove();

            });
        
    });
});

// Seacrh order modal show

$(document).on("click", "#search_order", function () {

    $('#search_order_modal').modal('show');
});

var auto_search_url = base_url+'/union-name-list-with-id-autocomplete-search';

// $( "#union_name" ).autocomplete({
//     minLength: 3,
//     source: auto_search_url
// });

$( "#union_name" ).autocomplete({
    minLength: 3,
    source: function( request, response ) {
      $.ajax({
        url: auto_search_url,
        //dataType: "jsonp", // comment if direct dropdown
        data: {
          query: request.term,
          device: $('.sp_device').val()
        },
        success: function( data ) {
          response( data );
        }
      });
    },
    select: function( event, ui ) {
        $('#union_name_val').val(ui.item.id);
        //console.log(ui.item.id+'ddddddd');
      //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
    },
    
  });

// edit price and stock

$(document).on("click", ".edit-price-stock", function () {
    editFeedId = $(this).data('id');
    $('#edit_vendor_modal .modal-title').html('Edit '+modal_title);
    $('#edit_vendor_modal').modal('show');
    $('#btn_add_feed').text('Edit');

    $('#edit_id').val($(this).data('user').id);

    $('#sale_price').val($(this).data('user').sale_price);
    $('#stock').val($(this).data('user').stock);
});

$(document).on("submit",".sp_form_edit_price_stock",function(event) {

    event.preventDefault();

    $('.sp_ajax_loader').remove();

    $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    
    var url             = base_url+'/admin/product-update-stock-by-vendor';
    var sale_price      = $('#sale_price').val();
    var stock           = $('#stock').val();
    var edit_id         = $('#edit_id').val();

    var data = {
        sale_price: sale_price,
        stock: stock,
        edit_id: edit_id
    };
    
    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

            setTimeout(function(){location.reload();},2500);

        } else {
            
            $('.msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('.sp_ajax_loader').remove();

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('.msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('.sp_ajax_loader').remove();

    });
    
});

$(document).on("click",".view_order_item_list",function(event) {

    event.preventDefault();

    modal_id = 'view_order_item_list_modal';

    $('#'+modal_id+' .ajax_content').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    $('#'+modal_id ).modal('show');
    
    var url = base_url+'/admin/view-order-item-list';

    var edit_id = $(this).data('id');

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

   var data = {
        edit_id: edit_id
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+'  .ajax_content').html(response.data.message);

        } else {
            
            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        $('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

});

$(document).on("click",".assign_to_phone_vendor",function(event) {

    event.preventDefault();

    modal_id = 'assign_to_phone_vendor_modal';

    $('#'+modal_id+' .ajax_content').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    $('#'+modal_id ).modal('show');
    
    var url = base_url+'/admin/order-assign-vendor-list';

    var edit_id = $(this).data('id');
    var order_id = $(this).data('order-id');
    var vendor_type = $(this).data('vendor_type');

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

   var data = {
        edit_id: edit_id,
        order_id: order_id,
        vendor_type: vendor_type
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+'  .ajax_content').html(response.data.message);

        } else {
            
            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        $('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

});

$(document).on("click",".update_assign_vendor_qty",function(event) {

    event.preventDefault();

    modal_id = 'assign_to_phone_vendor_modal';

    $('#'+modal_id+' .msg_status').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    var url = base_url+'/admin/order-update-assign-vendor-quantity';

    var vendor_order_total_qty = 0;
    $('.vendor_order_qty').each(function(){
        vendor_order_total_qty += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
    });

    var row_edit_id      =   $(this).data('id');
    var order_id         =   $(this).data('order-id');
    var order_item_id    =   $(this).data('order-item-id');
    var quantity         =   $('.vendor_order_qty_'+row_edit_id).val();
    var assign_user_id   =   $('.vendor_order_user_id_'+row_edit_id).val();
    var vendor_type      =   $(this).data('vendor_type');

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

   var data = {
        row_edit_id: row_edit_id,
        quantity: quantity,
        assign_user_id: assign_user_id,
        vendor_order_total_qty: vendor_order_total_qty,
        order_id: order_id,
        order_item_id: order_item_id,
        vendor_type: vendor_type,
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+' .msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

        } else {
            
            $('#'+modal_id+' .msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        $('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+' .msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

    setTimeout(function () {
        $(".alert").slideUp(800);
    }, 3000);

});

$(document).on("click",".sp_add_phone_vendor",function(event) {

    event.preventDefault();

    modal_id = 'assign_to_phone_vendor_modal';

    $('#'+modal_id+' .msg_status').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    var url = base_url+'/admin/order-assign-to-vendor';

    var vendor_order_total_qty = 0;
    $('.vendor_order_qty').each(function(){
        vendor_order_total_qty += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
    });

    var order_id         =   $(this).data('order-id');
    var order_item_id         =   $(this).data('order-item-id');
    var vendor_type         =   $(this).data('vendor_type');
    var assign_user_id        =   $('#phone_vendor_id').val();

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

   var data = {
        assign_user_id: assign_user_id,
        order_id: order_id,
        order_item_id: order_item_id,
        vendor_type: vendor_type,
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+' .msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

            sp_reload_rrder_assign_vendor_list( order_item_id, order_id , vendor_type );

        } else {
            
            $('#'+modal_id+' .msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');

            $('#'+modal_id+' .sp_ajax_loader').remove();
        }

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+' .msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

    setTimeout(function () {
        $(".alert").slideUp(800);
    }, 3000);

});

function sp_reload_rrder_assign_vendor_list( order_item_id , order_id , vendor_type ){

    modal_id = 'assign_to_phone_vendor_modal';
    
    var url = base_url+'/admin/order-assign-vendor-list';

    var data = {
        edit_id: order_item_id,
        order_id: order_id,
        vendor_type: vendor_type,
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+'  .ajax_content').html(response.data.message);

        } else {
            
            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        $('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+' .ajax_content').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });
}

$(document).on("click",".order-assign-vendor-delete-trigger",function(event) {	

    event.stopPropagation();
    var delete_id       =   $(this).attr('id');
    var order_id        =   $(this).data('order-id');
    var order_item_id   =   $(this).data('order-item-id');
    var vendor_type     =   $(this).data('vendor_type');

    swal({
        title: "Delete Permanently",
        text: "Are you Sure You wanted to Delete?",
        icon: "warning",
        buttons: {
            cancel : 'No, cancel please!',
            confirm : {text:'Yes, delete it!',className:'sweet-warning',
        closeModal: false,}
        },
        dangerMode: true,
    })
    .then((willDelete) => {

        if (willDelete) {

            $(".sa-confirm-button-container .confirm").prop('disabled',true);

            var url = base_url+'/admin/delete-order-assign-to-vendor';

            datastring = 'delete_id='+delete_id+'&vendor_type='+vendor_type;

            $.ajax({
                url:url,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                data:datastring,
                
                beforeSend: function()
                {
                    $(".sa-confirm-button-container .confirm").prop('disabled',true);
                    
                    $('.sa-confirm-button-container .confirm').html('<center><i class="fa fa-refresh fa-spin  fa-fw"></i> Wait...</center>');
                    
                },
                
                success: function(data)   
                {  
            
                    //var obs = $.parseJSON(data);
                    var obs = data;
                
                    if(obs['status']== true)
                    {  
                        swal("Deleted!", 'Record Delete Successfully !', "success");
                        sp_reload_rrder_assign_vendor_list( order_item_id, order_id , vendor_type );
                            
                    }
                    else{
                        
                        swal("Cancelled", 'Technical Error !', "error");
                    }
                    
                },

                error: function(data, textStatus, errorThrown)
                {
                    if(data.status == '403'){

                        swal("Cancelled", 'You does not have the right permissions.', "error");
                    }
                }
            });
            
            return false;
            
        } else {

            swal("Cancelled", "This process has been cancelled :)", "error");

        }
    });
});



