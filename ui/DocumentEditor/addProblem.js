/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function addProblem()
{
    entryUUID=$(this).attr("entryUUID");
    $("#problemDialog").attr("hidden",false);
    $("#problemDialog").attr("entryUUID",entryUUID);
    $("#txtProblem").select();
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
            $("#problemSearch td").click(function(){window.alert($(this).html())});
            $("#problemSearch tr").live({
                mouseover: function(){$(this).addClass("highlight")}
                ,mouseout: function(){$(this).removeClass("highlight")}
                });
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
    $("button[func='ADDPROB']").live({click: addProblem});
    $("#txtProblem").keypress(txtProblemKeyPress)
}