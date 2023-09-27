
$(function () {

    
    $(document).on("click",".modal-delete-trigger",function(event) {	

        event.stopPropagation();
        delete_id = $(this).attr('id');
        
        images = $(this).data("images");

        swal({
            title: "ลบข้อมูลนี้",
            text: "คุณกำลังจะลบข้อมูลนี้?",
            icon: "warning",
            buttons: {
                cancel : 'ไม่ต้องการลบ ~ ยกเลิก!',
                confirm : {text:'ใช่แล้ว, ต้องการลบ!',className:'sweet-warning',
            closeModal: false,}
            },
            dangerMode: true,
        })
        .then((willDelete) => {

            if (willDelete) {

                $(".sa-confirm-button-container .confirm").prop('disabled',true);

                var url = base_url+'/admin/union-membership-delete';

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
                            setTimeout(function(){location.reload();},2500);
                                
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

                swal("ยกเลิกแล้ว", "ระบบยกเลิกคำสั่งลบแล้ว :)", "error");

            }
        });
    });

    var editFeedId = 0;
    $('#addFeed').click(function () {
        $('.msg_status').html('');
        editFeedId = 0;
        $('#add_modal .modal-title').html('เพิ่มชื่อสมาชิกสหกรณ์');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Add');
        $('#union_name').val('');
        $('#union_member_ID').val('');
        $('#name').val('');
        $('#surname').val('');
        $('#address').val('');
        $('#phone_number').val('');
        $('#edit_id').val(0);
        $('#union_name').focus();
    });

    $(document).on("click", ".edit-user", function () {
        
        $('.msg_status').html('');
        
        editFeedId = $(this).data('id');
        $('#add_modal .modal-title').html('ปรับปรุงข้อมูลสมาชิกสหกรณ์');
        $('#add_modal').modal('show');
        $('#btn_add_feed').text('Edit');
        
        $('#union_name').val('');
        $('#union_member_ID').val('');
        $('#name').val('');
        $('#surname').val('');
        $('#address').val('');
        $('#phone_number').val('');
        
        $('#union_name').val($(this).data('user').union_name);
        $('#union_member_ID').val($(this).data('user').union_member_ID);
        $('#name').val($(this).data('user').name);
        $('#surname').val($(this).data('user').surname);
        $('#address').val($(this).data('user').address);
        $('#phone_number').val($(this).data('user').phone_number);
        $('#edit_id').val($(this).data('user').id);
    });

    //$('#btn_add_feed').click(function () {

    $(document).on("submit",".sp_form",function(event) {

        event.preventDefault();

        $('.sp_ajax_loader').remove();

        $('#add_modal .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
		
        var url = base_url+'/admin/union-membership-add';
        var union_name          =  $('#union_name').val();
        var union_member_ID     =  $('#union_member_ID').val();
        var name                =  $('#name').val();
        var surname             =  $('#surname').val();
        var address             =  $('#address').val();
        var phone_number        =  $('#phone_number').val();
        var edit_id             =  $('#edit_id').val();

        if( edit_id > 0 ){

            url = base_url+'/admin/union-membership-update';
        }

        var data = {
            union_name          : union_name,
            union_member_ID     : union_member_ID,
            name                : name,
            surname             : surname,
            address             : address,
            phone_number        : phone_number,
            edit_id             : edit_id,
        };
        
            axios.post(url, data).then(function (response) {
                console.log(response.data+'fetch');
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
