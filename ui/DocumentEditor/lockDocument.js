function ajaxLockDocument()
{

}
function lockDocument()
{
    $("#lockDialog").attr("hidden",false);
    $("#lockPass").select();

}

function lockButtonClicked()
{
    $("#lockDialog").attr("hidden",true);
    if($(this).attr("ID")=="lockDocument")
    {
        hash=SHA1($("#lockPass").val());

    }
    $("#lockPass").val("");

    if($(this).attr("ID")=="lockDocument")
    {
        docUUID=$(this).attr("docUUID");
        $.post("../../interface/lockDocument.php",
            {
                docUUID: ""+docUUID+"",
                password: ""+hash+""
            },
            function(data)
            {
              window.alert("Document Locked!\n"+data);  
            }
        );
    }
}

function registerLockEvents()
{
    $("button[func='lock']").live({click: lockDocument});
    $("#lockDialog").children("button").click(lockButtonClicked);
}