function handleAjaxError(event,XHR,settings,thrownError)
{
    msg = "Error:" + settings.url + ":" + XHR.responseText + ":" + thrownError;
    $("#status").html(msg);
}


