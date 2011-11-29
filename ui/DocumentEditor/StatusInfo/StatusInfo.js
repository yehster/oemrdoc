function ShowStatusMessage(msg)
{
    $("#StatusInfo").html(msg).show();
    setTimeout("$('#StatusInfo').hide();",2000);
}