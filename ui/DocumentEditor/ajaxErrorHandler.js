function handleAjaxError(event,XHR,settings,thrownError)
{
    msg = "Error:" + settings.url + ":" + XHR.responseText + ":" + thrownError;
    $("#status").html(msg);
    $("#status").attr("hidden",false);
    window.alert(msg);
    window.status=msg;
}


