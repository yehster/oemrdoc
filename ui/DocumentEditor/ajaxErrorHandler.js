function handleAjaxError(event,XHR,settings,thrownError)
{
    msg = "Error:"  + XHR.responseText + ":" +settings.url + ":"+ thrownError;
    $("#status").html(msg);
    $("#status").attr("hidden",false);
    window.alert(msg);
    window.status=msg;
}


