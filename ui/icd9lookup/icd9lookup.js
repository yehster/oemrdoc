function lookupProblem(searchString)
{
    currentTime=(new Date()).getTime();
    $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+"",
        },
        function(data)
        {
            prevTime=$("#problemSearch").attr("prevTime")
            if((prevTime==null)||(currentTime > prevTime))
            {
                $("#problemSearch").html(data);
                $("#problemSearch").attr("prevTime",currentTime);
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
    $("#txtProblem").keypress(txtProblemKeyPress);
}