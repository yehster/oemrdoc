var statMsgTO=null;
function ShowStatusMessage(msg)
{
    if(statMsgTO!=null)
        {
            clearTimeout(statMsgTO);
        }
    $("#StatusInfo").html(msg).show();
    statMsgTO=setTimeout("$('#StatusInfo').hide();",2000);
}