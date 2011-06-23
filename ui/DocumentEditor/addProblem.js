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

var t;
function txtProblemKeyPress()
{
clearTimeout(t);
t=setTimeout(function() {
        searchString=$("#txtProblem").val();
        length=searchString.length;
        $("#problemFavorites").text(searchString);

    },200)
}


function registerAddProblemEvents()
{
    $("button[func='ADDPROB']").live({click: addProblem});
    $("#txtProblem").keypress(txtProblemKeyPress)
}