function displayicd9results(table)
{
    var parent=$(problem_source).parent(".problemInput");
    var location=parent.find(".icd9");
    if(location.length==0)
        {
            location = $("<div></div>");
            location.addClass("icd9");
            location.attr("id","icd9");
            location.appendTo(parent);
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
    problem_source=this;
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
    var problemsInputSection=problemsLabel.siblings(".problemInput");
    if(problemsInputSection.length>0)
        {
            return
        }
        
    var problemsInput=$("<input type='text'/>");
    problemsInputSection=$("<span></span>")
    problemsInputSection.addClass("problemInput");
    problemsInputSection.append(problemsInput);
    problemsLabel.after(problemsInputSection);
    problemsInput.addClass("problems_input");
    problemsInput.on({
        keyup: process_problems
    });
    
    problemsInputSection.on({
        blur: function()
        {
            debugMessage("yo!")
            $("div.icd9").hide();
        },
        focus: function()
        {
            debugMessage("hi!")
            $("div.icd9").show();
            
        }
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