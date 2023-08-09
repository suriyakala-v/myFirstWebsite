
$(document).ready(function(){
    $("#btn_ok").click(function(){
        let user=$("#txt_username").val();
        let pswd=$("#txt_pwd").val();
        $.post("assets/php/pdocon.php",{
            username:user,
            password:pswd
        },
        function(data,status,xhr){
          let result=JSON.parse(xhr.responseText);
          if(result == "INVALID! user and password not match"){
            $('#err_msg').html(result);
          }
          else{
            window.location.href ='http://localhost/php/fullstackassessment/assessment/adminSection/manageProduct.php';
          }
        });
      });
});
