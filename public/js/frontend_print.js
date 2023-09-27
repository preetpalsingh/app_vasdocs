 // show add item modal

 $(document).on("click", ".sp_add_item_modal", function () {
    $('#add_modal').modal('show');
    $('#order_id').val(0);
});

var i=1;

$(document).on("submit",".sp_form",function(event) {

    event.preventDefault();

    modal_id = 'add_modal';

    $('.sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    //var url                         =   base_url+'/admin/add-union-order';
    var product_id                  =   $('#product_id').val();
    var product_name                  =   $('#product_id').find(':selected').text();
    var product_price               =   $('#product_id').find(':selected').attr('data-price');
    var simcard_id                  =   $('#simcard_id').val();
    var simcard_name                  =   $('#simcard_id').find(':selected').text();
    var product_quantity            =   $('#product_quantity').val();
    var receiver_name               =   $('#receiver_name').val();
    var receiver_delivery_address   =   $('#receiver_delivery_address').val();

    var product_total_price = product_price * product_quantity;

    var sp_append_text = '';
    var sp_append_hidden_input = '';

    if( product_id > 0 ){

        sp_append_hidden_input = '<input type="hidden" class="sp_tr_'+i+' product_id_val" value="'+product_id+'"><input type="hidden" class="sp_tr_'+i+' simcard_id_val" value="'+simcard_id+'"><input type="hidden" class="sp_tr_'+i+' product_quantity_val" value="'+product_quantity+'"><input type="hidden" class="sp_tr_'+i+' receiver_name_val" value="'+receiver_name+'"><input type="hidden" class="sp_tr_'+i+' receiver_delivery_address_val" value="'+receiver_delivery_address+'">';

        sp_append_text += '<tr class="sp_tr_'+i+'"><td class="text-center"><i  data-toggle="tooltip" data-original-title="Remove Item" aria-hidden="true" class="fas fa-trash text-danger sp_remove_item" style="border: 1px solid;padding: 5px;border-radius: 20px;font-size: 10px;cursor: pointer;" data-id="'+i+'"></i></td><td>'+product_name+'</td><td>'+simcard_name+'</td><td>'+product_quantity+'</td><td class="">'+product_price+'</td><td class="text-right sp_product_item_total">'+product_total_price+sp_append_hidden_input+'</td></tr>';

         

    }

    if( receiver_name != '' ){

        sp_append_text += '<tr class="sp_tr_'+i+'"><td></td><td class="">Alternative </td><td class="">'+receiver_name+' , '+receiver_delivery_address+' </td><td></td><td></td><td></td></tr>';
    }

    $('.sp_product_row').append(sp_append_text);

    setTimeout(function(){

        sp_calc_total();
        
        $('#'+modal_id).modal('hide');
        $('.sp_ajax_loader').remove();

        $('#'+modal_id+' form.sp_form')[0].reset();

        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

    },1000);

    i++;

});

$(document).on("click",".sp_remove_item",function(event) {

    $('.sp_product_row').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    row_id = $(this).data('id');

    $(this).tooltip('hide');

    $('.sp_tr_'+row_id).remove();

    setTimeout(function(){

        sp_calc_total();

        $('.sp_ajax_loader').remove();

    },1000);

});

function sp_calc_total()
{

    if ($(".sp_product_item_total")[0]){

        var order_sub_total = 0;
        $('.sp_product_item_total').each(function(){
            order_sub_total += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
        });

        order_sub_total = order_sub_total.toFixed(2);

        order_sales_tax = ( order_sub_total * 0.07 ).toFixed(2) ;

        order_total = parseFloat( order_sub_total ) + parseFloat( order_sales_tax );

        order_total = order_total.toFixed(2);

        $('.order_sub_total').html( priceFormat( order_sub_total ) );
        $('.order_sales_tax').html( priceFormat( order_sales_tax ) );
        $('.order_total').html( priceFormat( order_total ) );

        $('.sp_create_order').removeClass('disabled');

    } else {

        $('.order_sub_total').html( priceFormat( 0 ) );
        $('.order_sales_tax').html( priceFormat( 0 ) );
        $('.order_total').html( priceFormat( 0 ) );

        $('.sp_create_order').addClass('disabled');
    }

}

function priceFormat(numberString) {
    numberString += '';
    var x = numberString.split('.'),
        x1 = x[0],
        x2 = x.length > 1 ? '.' + x[1] : '',
        rgxp = /(\d+)(\d{3})/;

    while (rgxp.test(x1)) {
        x1 = x1.replace(rgxp, '$1' + ',' + '$2');
    }

    return x1 + x2;
}

$(document).on("click",".sp_create_order",function(event) {

    $('.sp_create_order').addClass('disabled');

    $('.sp_create_order_loader').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');

    var product_ids = [];

    i = 0;

    $('.product_id_val').each(function() {

        product_ids[i++] = $(this).val();

    });

    var simcard_ids = [];

    i = 0;

    $('.simcard_id_val').each(function() {

        simcard_ids[i++] = $(this).val();
        
    });

    var product_quantitys = [];

    i = 0;

    $('.product_quantity_val').each(function() {

        product_quantitys[i++] = $(this).val();
        
    });

    var receiver_names = [];

    i = 0;

    $('.receiver_name_val').each(function() {

        receiver_names[i++] = $(this).val();
        
    });

    var receiver_delivery_addresss = [];

    i = 0;

    $('.receiver_delivery_address_val').each(function() {

        receiver_delivery_addresss[i++] = $(this).val();
        
    });

    po_number = $('#po_number').val();

    var url = base_url+'/union/create-union-order';

    var data = {
        po_number: po_number,
        product_ids: product_ids,
        simcard_ids: simcard_ids,
        product_quantitys: product_quantitys,
        receiver_names: receiver_names,
        receiver_delivery_addresss: receiver_delivery_addresss,
    };

    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('.msg_status').html('<div class="alert alert-success">'+response.data.message+'</div>');

            setTimeout(function () {
                window.location.href = base_url+'/union/view-order-union-quotation/'+response.data.order_id;
            }, 3200);

        } else {
            
            $('.msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');

            $('.sp_create_order').removeClass('disabled');

        }

        $('.sp_ajax_loader').remove();

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('.msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }

        $('.sp_create_order').removeClass('disabled');
        
        $('.sp_ajax_loader').remove();

    });

    setTimeout(function () {
        $(".alert").slideUp(800);
    }, 3000);

});



$(document).on("change",".sp_get_stock_by_product_id",function(event) {

    event.preventDefault();

    modal_id = 'add_modal';

    $('#'+modal_id+' .msg_status').html('');

    $('#'+modal_id+'  .sp_show_stock').html('');

    $('#'+modal_id+' .sp_ajax_loader').remove();

    $('#'+modal_id+' .modal-content').append('<div class="sp_custom_loader sp_ajax_loader" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); opacity: 0.6; cursor: default; position: absolute;"></div>');
    
    
    var url                 = base_url+'/union/check-stock-by-product-id';
    var product_id          = $(this).val();

    var data = {
        product_id: product_id,
    };
    
    axios.post(url, data).then(function (response) {

        if (response.data.status == true) {

            $('#'+modal_id+'  .sp_show_stock').html(response.data.message);

        } else {

            $('#'+modal_id+' .msg_status').html('<div class="alert alert-danger">'+response.data.message+'</div>');
            
        }

        $('#'+modal_id+' .sp_ajax_loader').remove();

    }).catch(function (error) {

        if (error == 'Error: Request failed with status code 403') {

            $('#'+modal_id+'  .msg_status').html('<div class="alert alert-danger">You does not have the right permissions.</div>');
        }
        
        $('#'+modal_id+'  .sp_ajax_loader').remove();

    });

});