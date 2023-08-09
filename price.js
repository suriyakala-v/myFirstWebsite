$(document).ready(function(){
    $('.quantity').on('change',function(){
        let tot_price=0;
        $('.quantity').each(function(index){
            let price=Number($(this).val());
            tot_price=tot_price+price;
        });
        $('#totalPrice').html(tot_price);
    });
});