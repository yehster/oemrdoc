function ajaxLockDocument()
{

}
function lockDocument()
{
    $("#lockDialog").show();
    $("#lockPass").select();

}

function lockButtonClicked()
{
    $("#lockDialog").hide();
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
                window.alert(data);
              if(data==docUUID)  
                  {
                      window.alert("Document Locked!\n");                
                  }
                  window.location.reload();
            }
        );
    }
}

function registerLockEvents()
{
    $("button[func='lock']").live({click: lockDocument});
    $("#lockDialog").children("button").click(lockButtonClicked);
}