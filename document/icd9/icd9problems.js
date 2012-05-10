function displayicd9results(table)
{
    var location=$("#icd9");
    if(location.length==0)
        {
            location = $("<div></div>");
            location.addClass("icd9");
            location.attr("id","icd9");
            location.appendTo("body");
        }
   location.html(table);
//   location.find("td[defs]").append("<button>...</button>");
}
function icd9results(data)
{
    displayicd9results(data.codes);
}

function lookup_problem(problem)
{
    var requestTime=new Date().getTime();
    $.post("/openemr/library/doctrine/icd9dictionary/lookupKeywords.php",
        {
            searchString: problem,
            requestTime: requestTime
        },
        icd9results,
        "json"
    );    
}
function process_problems()
{
    var problem = $(this).val();
    if(typeof(lookup_timer)!='undefined')
    {
        clearTimeout(lookup_timer);
    }
    var lookup_code="lookup_problem(\'"+problem+"\')";
//    lookup_problem(problem);
    lookup_timer=setTimeout(lookup_code,300);
    debugMessage(problem);
    
}

function setupProblems(parent)
{
    var problemsLabel=parent.find("section[entrytype='Section'] > span.label:contains('Problem List')");
    if(problemsLabel.length==0)
        {
            return;
        }
    var problemsInput=$("<input type='text'/>");
    problemsLabel.after(problemsInput);
    problemsInput.addClass("problems_input");
    problemsInput.on({
        keyup: process_problems
    });
    // 
    var content=problemsLabel.siblings("span.content");
    var list=$("<ol></ol>");
    content.children().each(function(idx,elem)
    {
        var li=$("<li></li>");
        li.append(elem);
        list.append(li);
    }
    );
    content.append(list);
}