$().ready(function() {  
    $("#login_form").validate({  
        "errorElement":"i",
        rules:{
            uname:{
                required:true,
            },
            password:{
                required: true,  
                minlength: 5  
            },
        },
        messages: {  
            uname: "请输入姓名",  
            password: {  
                required: "请输入密码",  
                minlength:  $.validator.format("密码最少为 {0} 位"),
            },
           
        }  
    });  
    $("#register_form").validate({  
        rules: {  
            uname: "required",  
            password: {  
                required: true,  
                minlength: 5  
            },  
            tel_num:{
                required: true,
                maxlength:11,
                minlength:11
            }  
        },  
        messages: {  
            uname: "请输入姓名",  
            password: {  
                required: "请输入密码",  
                minlength:  $.validator.format("密码最少为 {0} 位"),
            },  
            tel_num:{
                required: "请输入手机号码",
                minlength:  $.validator.format("手机号最少为 {0} 位"),
                maxlength:  $.validator.format("手机号最多为 {0} 位"),
            }  
        },  
    });  
});  
$(function() {  
    $("#register_btn").click(function() {  
        $("#register_form").css("display", "block");  
        $("#login_form").css("display", "none");  
    });  
    $("#back_btn").click(function() {  
        $("#register_form").css("display", "none");  
        $("#login_form").css("display", "block");  
    });  
});  