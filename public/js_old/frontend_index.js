
// show modal

$(document).on("click", ".show_Union_Login_modal", function () {
    $('#Union_Login_modal').modal('show');
});

// show modal

$(document).on("click", ".show_Union_Membership_modal", function () {
    $('#Union_Membership_modal').modal('show');

    var product_id = $(this).data('id');
    
    $('#simcard').val($('.sp_simcard_'+product_id+'.active').html());
    $('#product_id').val(product_id);
});

$(document).on("submit",".sp_Union_Membership_form",function(event) {

    event.preventDefault();

    modal_id = 'Union_Membership_modal';

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    
    var url                     = base_url+'/verify-union-membership';
    var redirect_url            = base_url+'/create-union-membership-order-quotation';
    var union_name              = $('#union_name').val();
    var union_member_id         = $('#union_member_id').val();
    var to_detail               = $('#to_detail').val();
    var main_shipping_location  = $('#main_shipping_location').val();
    var quantity                = $('#quantity').val();
    var simcard                 = $('#simcard').val();
    var product_id              = $('#product_id').val();

    var data = {
        union_name: union_name,
        union_member_id: union_member_id,
        to_detail: to_detail,
        main_shipping_location: main_shipping_location,
        quantity: quantity,
        simcard: simcard,
        product_id: product_id,
    };
    
        axios.post(url, data).then(function (response) {

            if (response.data.status == true) {

                //$('#'+modal_id+'  .msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

                $('#Union_Membership_modal').modal('hide');

                swal(
                    "Membership Verified", 
                    '', 
                    "success",
                    {
                        className: "sp_sweet_alert_success",
                    }
                  );

                $('.sp_Union_Membership_form')[0].reset();

                setTimeout(function(){

                    window.location.href = redirect_url;
                    
                    //location.reload();

                },2000);

            } else {

                if( response.data.message == 'Detail not found.' ){

                    $('#Union_Membership_modal').modal('hide');

                    swal("Membership Unverified", "", "error", {
                        className: "sp_sweet_alert_danger",
                      });

                } else {

                    $('#'+modal_id+'  .msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');

                }

                
            }

            $('#'+modal_id+' .sp_ajax_loader').remove();

        }).catch(function (error) {

            if (error == 'Error: Request failed with status code 403') {

                $('#'+modal_id+'  .msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
            }
            
            $('#'+modal_id+'  .sp_ajax_loader').remove();

        });
    
});

// show modal

$(document).on("click", ".union_membership_quotation_modal", function () {

    $('#Union_Membership_Quotation_modal').modal('show');

    var product_id = $(this).data('id');
    
    $('#simcard').val($('.sp_simcard_'+product_id+'.active').html());
    $('#product_id').val(product_id);
});

$(document).on("click", ".sp_simcard", function () {

    var product_id = $(this).data('id');

    $('.sp_simcard_'+product_id).removeClass('active');

    $(this).addClass('active');

    $('#simcard').val($('.sp_simcard_'+product_id+'.active').html());
    $('#product_id').val(product_id);

});

$(document).on("submit",".sp_Union_Login_form",function(event) {

    event.preventDefault();

    modal_id = 'Union_Login_modal';

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    
    var url                 = base_url+'/union-login';
    var redirect_url            = base_url+'/union/create-union-order-view/';
    var email          = $('#email').val();
    var password     = $('#password').val();

    var data = {
        email: email,
        password: password,
    };
    
    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+'  .msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

            $('.sp_Union_Login_form')[0].reset();

            setTimeout(function(){

                window.location.href = redirect_url;
                
                //location.reload();

            },2000);

        } else {

            $('#'+modal_id+'  .msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
            
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+'  .msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+'  .sp_ajax_loader').remove();

    });
    
});



$(document).on("click",".view_order_item_list",function(event) {

    event.preventDefault();

    modal_id = 'view_order_item_list_modal';

    $('#'+modal_id+' .ajax_content').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    $('#'+modal_id ).modal('show');
    
    var url = base_url+'/union/union-order-item-list';

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

$(document).on("click",".sp_load_more",function(event) {

    event.preventDefault();

    modal_id = 'sp_load_more_container';

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id).append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    var url = base_url+'/product-load-more';

    var last_order_id = $('#last_order_id').val();

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

    var data = {
        last_order_id: last_order_id
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true ) {

            if (response.data.last_order_id > 0 ) {

                $('#'+modal_id).children(':first').append(response.data.message);

                $('#last_order_id').val( response.data.last_order_id );

            } else {

                $('.sp_load_more').remove();

            }

        } else {
            
            $(this).parent().append('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        //$('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $(this).parent().append('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

});

// show Upload Union Signed Document Modal

$(document).on("click", ".sp_upload_order_document", function () {

    $('.sp_upload_union_signed_document_form')[0].reset();
			
    $('input[name="order_id"]').val($(this).data('id'));

    $('input[name="file_prev"]').val($(this).data('file'));

    $('#upload_union_signed_document_modal').modal('show');

});

var submissionEnabled = true;

$(document).on("submit","#sp_upload_union_signed_document_form",function(event) {

    event.preventDefault();

    modal_id = 'upload_union_signed_document_modal';

    //$('#'+modal_id+' .sp_ajax_loader').remove();

    if( submissionEnabled ) {
		
		submissionEnabled = false;
		
		form_id = 'sp_upload_union_signed_document_form';
	
		$('#'+form_id+' .sp_form_submit').html('Wait..');
		
		var fileName = $("input[name='file']").val();
		//var form = $('#'+form_id)[0];
		var formData = new FormData(this);

        var url = base_url+'/union/upload-union-order-signed-document';

		$.ajax({
            xhr: function() {
				
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						
						if( fileName != '' ){
						
							var percentComplete = parseInt(((evt.loaded / evt.total) * 100));
							$("#progress-bar").width(percentComplete + '%');
							$("#progress-bar").html(percentComplete+'%');
						
						}
					}
					
					if( percentComplete == 100){
						//$('.moving').attr('style' , 'display: unset;');
						$('.moving').css({"display": "flex"});
						
					}
					
				}, false);
				return xhr;
            },
			type:'POST',
			url: url,
			//url: "",
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
            beforeSend: function(){
                $("#progress-bar").width('0%');
                $('#loader-icon').show();
            },
			success:function(response){
				$('.moving').css({"display": "none"});
				//var response = jQuery.parseJSON(response);
				
                if (response['status'] == true) {

                    $('#'+modal_id+'  .msg_status').html('<div class="alert badge-success text-white">'+response['message']+'</div>');
        
                    $('#'+form_id)[0].reset();
        
                    setTimeout(function(){
        
                        //window.location.href = redirect_url;
                        
                        location.reload();
        
                    },2000);
        
                } else {
        
                    $('#'+modal_id+'  .msg_status').html('<div class="alert badge-danger text-white">'+response['message']+'</div>');

                    $("#progress-bar").width('0%');

                    $('#'+form_id+' .sp_form_submit').html('Upload');
                    
                }
				
				submissionEnabled = true;
			},
			error: function(error){

				if (error == 'Error: Request failed with status code 403') {

                    $('#'+modal_id+'  .msg_status').html('<div class="alert badge-danger text-white">You does not have the right permissions.</div>');

                } else {

                    $('#'+modal_id+'  .msg_status').html('<div class="alert badge-danger text-white">Technical Error !</div>');
                }

				$('.moving').css({"display": "none"});

                $("#progress-bar").width('0%');

                $('#'+form_id+' .sp_form_submit').html('Upload');
                
                $('#'+modal_id+'  .sp_ajax_loader').remove();
			} 
		});
		
	}
    
});

// cancel union order



$(document).on("click",".sp_cancel_order",function(event) {	

    event.stopPropagation();
    delete_id = $(this).attr('id');
    
    images = $(this).data("images");

    swal({
        title: "Cancel Permanently",
        text: "Are you Sure You wanted to Cancel?",
        icon: "warning",
        buttons: {
            cancel : 'No, cancel please!',
            confirm : {text:'Yes, cancel it!',className:'sweet-warning',
        closeModal: false,}
        },
        dangerMode: true,
    })
    .then((willDelete) => {

        if (willDelete) {

            $(".sa-confirm-button-container .confirm").prop('disabled',true);

            var url = base_url+'/union/order-cancel';

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
                        swal("Deleted!", 'Record Cancelled Successfully !', "success");
                        setTimeout(function(){location.reload();},3000);
                            
                    }
                    else{
                        
                        //swal("Cancelled", 'Technical Error !', "error");
                        swal("Cancelled", obs['message'], "error");
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

var auto_search_url = base_url+'/union-name-list-autocomplete-search';

$( "#union_name" ).autocomplete({
    minLength: 4,
    source: auto_search_url
});

var auto_search_url_phone = base_url+'/device-list-autocomplete-search';

// $( ".sp_device_search" ).autocomplete({
//     minLength: 4,
//     source: auto_search_url_phone
// });

$( ".sp_device_search" ).autocomplete({
    minLength: 3,
    source: function( request, response ) {
      $.ajax({
        url: auto_search_url_phone,
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
      //getContentWithSearch( request.term, 'SAMSUNG' );

      device = $('.sp_device').val();

      getContentWithSearch( ui.item.id, device );
      //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
    },
    // open: function() {
    //   $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
    // },
    // close: function() {
    //   console.log('close click');
    // },
    // change: function() {
    //   console.log('close click');
    // },
    // focus: function(event, ui) {
    //     console.log('close clic22k');
    //   return false;
    // }
  });

  $(document).on("clcik","#search-clear",function(event) {
    console.log('close click');
  });

  function getContentWithSearch(seacrh, device){

    modal_id = 'sp_load_more_container';

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id).append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    var url = base_url+'/product-list-with-search';

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

    var data = {
        seacrh: seacrh,
        device: device,
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true ) {

            if (response.data.last_order_id > 0 ) {

                $('#'+modal_id).children(':first').html(response.data.message);

                $('#last_order_id').val( response.data.last_order_id );
                $('.sp_load_more').hide();

            } else {

                $('.sp_load_more').remove();

            }

        } else {
            
            $(this).parent().append('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        //$('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $(this).parent().append('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });
  }

  

$(document).on("click",".sp_reset_seacrh",function(event) {

    event.preventDefault();

    modal_id = 'sp_load_more_container';

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id).append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    var url = base_url+'/reset-product-search';

    var last_order_id = $('#last_order_id').val();

   // datastring = 'delete_id='+delete_id+'&table_name=<?php echo $table_name;?>'+'&images='+images;

    var data = {
        last_order_id: last_order_id
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true ) {

            if (response.data.last_order_id > 0 ) {

                $('#'+modal_id).children(':first').html(response.data.message);

                $('#last_order_id').val( response.data.last_order_id );

                $('.sp_load_more').show();

            } else {

                $('.sp_load_more').remove();

            }

        } else {
            
            $(this).parent().append('<div class="alert alert-danger">'+response.data.message+'</div>');
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

        //$('#'+modal_id).tooltip({selector: '[data-toggle="tooltip"]'});

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $(this).parent().append('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+' .sp_ajax_loader').remove();

    });

});



