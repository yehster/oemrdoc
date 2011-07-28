/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function addProblemStart()
{
    entryUUID=$(this).attr("entryUUID");
    $("#problemDialog").show();
    $("#problemDialog").attr("entryUUID",entryUUID);
    $("#txtProblem").select();
}

function clearAndHideProblemDialog()
{
    $("#problemDialog").hide();
    $("#txtProblem").val("");
    $("#problemSearch").html("");
    
    
}

function createProblem(parentUUID,code,codeType,text)
{
    $.post("../../interface/manageProblem.php",
    {
        parentUUID: ""+parentUUID+"",
        code: ""+code+"",
        codeType: ""+codeType+"",
        text: ""+text+"",
        task: "create",
        refresh: "YES"
    },
    function(data)
    {
        refreshEntry(parentUUID,data);
        clearAndHideProblemDialog();
    }
    );
}

function lookupProblem(searchString)
{
    currentTime=(new Date()).getTime();
    $("#problemSearch").attr("prevTime",currentTime);
    $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+"",
        },
        function(data)
        {
            prevTime=$("#problemSearch").attr("prevTime")
            if((prevTime==null)||(currentTime >= prevTime))
            {
                $("#problemSearch").html(data);
                $("#problemSearch td[type='CODETEXT']").click(
                function(){
                    entryUUID=$("#problemDialog").attr("entryUUID");
                    code=$(this).attr("code");
                    codeType=$(this).attr("codetype")
                    text=$(this).text();
                    createProblem(entryUUID,code,codeType,text);
                }
                );
                $("#problemSearch tr").mouseover(function(){$(this).addClass("highlight")});
                $("#problemSearch tr").mouseout(function(){$(this).removeClass("highlight")});

            }
            
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

function useTxtProblem()
{
    entryUUID=$("#problemDialog").attr("entryUUID");
    code="";
    codeType="";
    text=$("#txtProblem").val();
    createProblem(entryUUID,code,codeType,text);
}



function registerAddProblemEvents()
{
    $("button[func='ADDPROB']").live({click: addProblemStart});
    $("#txtProblem").keypress(txtProblemKeyPress);
    $("#useTxtProblem").click(useTxtProblem)
    $("#cancelProblem").click(clearAndHideProblemDialog);
}