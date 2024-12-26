let taxRate = 0;
let total = 0;
let subTotal = 0;
let discount = 0;
$(document).ready(function() {
    $('#rateWrapper').hide();
    $('#flatWrapper').hide();
    $('.js-example-basic-single').select2();
    $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());

    $(document).on('change', '#invoice_type', function(e){
        e.preventDefault();
    });
    $(document).on('change', '#discountType', function(e){
        e.preventDefault();
        let select = $(this).val();
        if(parseInt(select) === 1){
            $('#rateWrapper').hide();
            $('#flatWrapper').show();
        }else if(parseInt(select) === 2){
            $('#rateWrapper').show();
            $('#flatWrapper').hide();
        }else{
            $('#rateWrapper').hide();
            $('#flatWrapper').hide();
        }
    });
    $('#discountAmount').on('blur', function(e){
        e.preventDefault();
        discount = $(this).val();
        calculateTotals();

    });
    $('#discountRate').on('blur', function(e){
        e.preventDefault();
        const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
        const total = subTotals.reduce((a, v)=> a + Number(v), 0);
        let tax = (taxRate/100).toFixed(2) * total;
        let discountRate  = $(this).val() || 0;
        let gross = tax + total;
        let discountAmount = (discountRate/100).toFixed(2) * gross;
        discount = discountAmount;
        calculateTotals();

    });
    //estate-info
    $('#property').on('change', function(e){
        e.preventDefault();
        let price =  $(this).find(':selected').data('price')
        $('#propertyPrice').html(`<strong style='color:#ff0000;'><small>(Price: ${price.toLocaleString()})</small></strong>`)
        axios.post("{{route('estate-info')}}",{id:$(this).val()})
            .then(res=>{
                taxRate = res.data.estate.tax_rate;
                $('#propertyEstate').text(res.data.estate.e_name);
                $('#propertyAmount').html(price.toLocaleString());
                $('#propertyPaymentPlan').text(`${res.data.paymentPlanName} - ${res.data.paymentPlanDesc}`);
                $('#taxRate').text(taxRate);
            });
        calculateTotals();
    });

    $(document).on('click', '.add-line', function(e){
        e.preventDefault();
        let new_selection = $('.item').first().clone();
        $('#products').append(new_selection);

        $(".select-product").select2({
            placeholder: "Select service"
        });
        $(".select-product").last().next().next().remove();
    });
    //Remove line
    $(document).on('click', '.remove-line', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateTotals();
    });

});

function setTotal(){
    let sum = 0;
    $(".payment").each(function(){
        sum += +$(this).val().replace(/,/g, '');
        $(".total").text(sum.toLocaleString());
    });
}
function setTax(){
    let sum = 0;
    $(".payment").each(function(){
        sum += +$(this).val().replace(/,/g, '');
        //$(".tax").text(123);
    });
}
//calculate totals
function calculateTotals(){
    const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
    const total = subTotals.reduce((a, v)=> a + Number(v), 0);
    grand_total = total;
    let tax = (taxRate/100).toFixed(2) * total;

    $('.sub-total').text(grand_total.toLocaleString());
    $('#subTotal').val(total);
    $('#totalAmount').val(grand_total);
    $('.subTotal').text((total).toLocaleString());
    $('.total').text(( (total+tax) - discount).toLocaleString());
    $('.tax').text(tax.toLocaleString());
    $('.discount').text(parseFloat(discount).toLocaleString());
    $('.balance').text(total.toLocaleString());
}

//calculate subtotals
function calculateSubTotal(row){
    const $row = $(row);
    const inputs = $row.find('input');
    const subtotal = inputs[0].value * inputs[1].value;
    $row.find('td:nth-last-child(2) input[type=text]').val(subtotal);
    return subtotal;
}

$('.aggregate').on('change', function(e){
    e.preventDefault();
    //setTotal();
    //setTax();
});
