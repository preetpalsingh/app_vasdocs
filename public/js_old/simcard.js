
$(function () {

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

                var url = base_url+'/admin/simcard-delete';

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
        $('#add_modal .modal-title').html('Add '+modal_title);
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Add');

        $('#edit_id').val(0);
        $('#simcard_provider').focus();
        
        $('#simcard_provider').val('');
        $('#monthly_price').val('');
        $('#period').val('');
        $('#description').html('');
    });

    $(document).on("click", ".edit-user", function () {
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('Edit '+modal_title);
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');

        $('#edit_id').val($(this).data('user').id);

        $('#simcard_provider').val($(this).data('user').simcard_provider);
        $('#monthly_price').val($(this).data('user').monthly_price);
        $('#period').val($(this).data('user').period);
        $('#description').html($(this).data('user').description);
    });

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
		
        var url                     = base_url+'/admin/simcard-add';
        var simcard_provider        = $('#simcard_provider').val();
        var monthly_price           = $('#monthly_price').val();
        var period                  = $('#period').val();
        var description             = $('#description').val();
        var edit_id                 = $('#edit_id').val();

        if( edit_id > 0 ){

            url = base_url+'/admin/simcard-update';
        }

        var data = {
            simcard_provider: simcard_provider,
            monthly_price: monthly_price,
            period: period,
            description: description,
            edit_id: edit_id,
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


