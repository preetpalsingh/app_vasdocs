
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

                var url = base_url+'/admin/product-delete';

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
    $('#addFeed').click(function () {
        $('.msg_status').html('');
        editFeedId = 0;
        $('#add_modal .modal-title').html('เพิ่มข้อมูล');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Add');

        $('#edit_id').val(0);
        $('#previous_img_val').val(0);
		$('#img_url').val("");
		$('#img_val').val("");
        $('#title').focus();
        
        $('#title').val('');
        $('#price').val('');
        $('#link_spec').val('');
    });

    $(document).on("click", ".edit-user", function () {
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('แก้ไขข้อมูล');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');

        $('#edit_id').val($(this).data('user').id);
        $('#previous_img_val').val($(this).data('user').image);

        $('#title').val($(this).data('user').title);
        $('#price').val($(this).data('user').price);
        $('#link_spec').val($(this).data('user').link_spec);
    });

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
		
        var url             = base_url+'/admin/product-add';
        var title           = $('#title').val();
        var price           = $('#price').val();
        var link_spec       = $('#link_spec').val();
        var edit_id         = $('#edit_id').val();

        if( edit_id > 0 ){

            url = base_url+'/admin/product-update';
        }

        var previous_img_val = $('#previous_img_val').val();
        var data = {
            title: title,
            price: price,
            link_spec: link_spec,
            edit_id: edit_id,
            previous_img_val: previous_img_val,
            image: $('#img_val').val()
        };
        
            axios.post(url, data).then(function (response) {
                
                $('#img_url').val("");

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

// edit price and stock

$(document).on("click", ".edit-price-stock", function () {
    editFeedId = $(this).data('id');
    $('#edit_vendor_modal .modal-title').html('ปรับราคาและ Stock');
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



$(document).on("click",".view-price-stock",function(event) {

    event.preventDefault();

    $('.msg_status').html('');

    $('.sp_ajax_loader').remove();

    $('#viewt_vendor_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    $('#viewt_vendor_modal').modal('show');
    
    var url = base_url+'/admin/product-view-stock-by-vendor';

    var edit_id = $(this).data('id');

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

   var data = {
        edit_id: edit_id
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#viewt_vendor_modal .msg_status').html(response.data.message);

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

