function lockDocument()
{
    docUUID=$(this).attr("docUUID");
    $.post("../../interface/lockDocument.php",
            {
                docUUID: ""+docUUID+""
            },
            function(data)
            {
              window.alert(data);  
            }
        );
}

function registerLockEvents()
{
    $("button[func='lock']").live({click: lockDocument});
}