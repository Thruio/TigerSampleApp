/**
 * Created by Sebastian on 2015-08-30.
 */
function error(field)
{
    $(".form-group-" + field).each(function(){
        $(this).addClass("has-error").removeClass("has-success");;
    });
    $(".form-control-feedback-" + field).removeClass("glyphicon-ok").addClass("glyphicon-remove");
    //$(".form-control-feedback-" + field).removeClass("glyphicon-ok")
    $(".btn-register").prop("disabled",true);
}

function success(field)
{
    $(".form-group-" + field).each(function(){
        $(this).removeClass("has-error").addClass("has-success");
    });
    $(".form-control-feedback-" + field).removeClass("glyphicon-remove").addClass("glyphicon-ok");
}

function pass_check()
{
    var p1 = $("#password").val();
    var p2 = $("#password2").val();
    //alert("[" + p1 + "] - [" + p2 + "]");
    if(p1.length < 6) {
        error("password");
        $("#password").tooltip("Too Short");
    }
    else {
        success("password")
        if (p2 != p1)error("password2");
        else
        {
            success("password2");
            return true;
        }
    }
    return false;
}

function user_check(val)
{
    var regx = /^[A-Za-z0-9]+$/;
    if (!regx.test(val) || val.length < 3)error("username");
    else
    {
        success("username");
        return true;
    }
    return false;
}

function email_check(val)
{
    //var regx = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var pass = false;
    var index = val.indexOf("@");
    if(index >= 0 && val.length > index + 3)
    {
        val = val.substring(index + 1);
        index = val.indexOf(".");
        if(index > 0 && val.length > index + 1) pass = true;
    }
    if (!pass)error("email");
    else
    {
        success("email");
        return true;
    }
    return false;
}
var userGood = false;
var emailGood = false;
var passGood = false;
$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    $(".btn-register").prop("disabled",true);
    $(".form-check").keyup(function(){
        var id = $(this).attr("id");
        var val = $(this).val();
        switch (id)
        {
            case "password":
            case "password2":
                passGood = pass_check();
                break;
            case "username":
                userGood = user_check(val);
                break;
            case "email":
                emailGood = email_check(val);
                break;
            default :
                break;
        }
        if(userGood && emailGood && passGood) $(".btn-register").prop("disabled",false);
        else $(".btn-register").prop("disabled",true);
    });
    $(".confirm-field").keyup(function(){
        $("#" + $(this).attr("id") + "Confirm").val($(this).val());
    });
});