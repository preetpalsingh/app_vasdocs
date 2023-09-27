
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

                var url = base_url+'/admin/log-delete';

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

    
});
