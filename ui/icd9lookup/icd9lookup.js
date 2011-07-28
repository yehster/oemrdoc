function lookupProblem(searchString)
{

    $("#problemLoading").show();
    currentTime=(new Date()).getTime();
    $("#problemSearch").attr("prevTime",currentTime);
    $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+"",
            codeSet: "('2','3','9','16','V')"
        },
        function(data)
        {
            $("#problemLoading").hide();
            prevTime=$("#problemSearch").attr("prevTime")
            if((prevTime==null)||(currentTime >= prevTime))
            {
                $("#problemSearch").html(data);
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

function registerICD9lookupEvents()
{
    $("#problemLoading").hide();
    $("#txtProblem").keypress(txtProblemKeyPress);
}