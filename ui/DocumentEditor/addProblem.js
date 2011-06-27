/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function addProblemStart()
{
    entryUUID=$(this).attr("entryUUID");
    $("#problemDialog").attr("hidden",false);
    $("#problemDialog").attr("entryUUID",entryUUID);
    $("#txtProblem").select();
}

function createProblem(parentUUID,code,codeType,text)
{
    window.alert(text);
    $.post("../../interface/manageProblem.php",
    {
        parentUUID: ""+parentUUID+"",
        code: ""+code+"",
        codeType: ""+codeType+"",
        text: ""+text+"",
        task: "create"
    },
    function(data)
    {
        window.alert(data);
    }
    );
}

function lookupProblem(searchString)
{
    $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+""
        },
        function(data)
        {
            $("#problemSearch").html(data);
            $("#problemSearch td[type='CODETEXT']").click(
            function(){
                entryUUID=$("#problemDialog").attr("entryUUID");
                code=$(this).attr("code");
                codeType=$(this).attr("codetype")
                text=$(this).text();
                window.alert(entryUUID);
                createProblem(entryUUID,code,codeType,text);
            });
            $("#problemSearch tr").mouseover(function(){$(this).addClass("highlight")});
            $("#problemSearch tr").mouseout(function(){$(this).removeClass("highlight")});
            
        }
    );
}

var t;
function txtProblemKeyPress()
{
clearTimeout(t);
t=setTimeout(function() {
        searchString=$("#txtProblem").val();
        length=searchString.length;
        $("#problemFavorites").text(searchString);
        lookupProblem(searchString);
    },200)
}


function registerAddProblemEvents()
{
    $("button[func='ADDPROB']").live({click: addProblemStart});
    $("#txtProblem").keypress(txtProblemKeyPress)
}