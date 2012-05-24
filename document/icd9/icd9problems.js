function displayicd9results(data)
{
    var table=data.codes;
    var type=data.type;
    var parent=$(problem_source).parent(".problemInput");
    var location=parent.find(".icd9");
    if(location.length==0)
        {
            location = $("<div></div>");
            location.addClass("icd9");
            location.attr("id","icd9");
            location.appendTo(parent);
        }
   location.append(table);
//   location.find("td[defs]").append("<button>...</button>");
    bind_problem_table_events(location);
}
function add_problem(evt)
{
    var di=new doctrineInfo($(problem_source).parents("['uuid']:first"));
    var parentUUID=di.uuid;
    var text=$(this).text();
    var code=$(this).siblings("td.codeNum").text();
    var requestTime=new Date().getTime();
    debugMessage(parentUUID+":"+text+":"+code);
    $.post("/openemr/library/doctrine/interface/manageProblem.php",
    {
        parentUUID: parentUUID,
        code: code,
        codeType: 2,
        text: text,
        task: "create",
        refresh: "doc",
        addNarrative: "YES",
        requestTime: requestTime
    },
    function(data)
    {
        refreshSection(data);
    },
    "json"
    );    
}
function show_parent_info(evt)
{
    var row=$(this);
    var codeNum=row.children(".codeNum").text();
    var children=row.parent().children("[parent_code='"+codeNum+"']").show();
    if(children.length==0)
        {
            
        }
}
function bind_problem_table_events(parent)
{
    
    parent.find("tr[type='SP'] td.codeDesc").on(
    {
        click: add_problem
    });
    parent.find("tr[type='NS']").on(
    {   click: show_parent_info}
    );
    parent.find("tr[type='Section']").on(
    {   click: show_parent_info}
    );
}
function icd9results(data)
{
        displayicd9results(data);    

}

function lookup_problem(problem)
{
    var requestTime=new Date().getTime();
    var requestURL="/openemr/library/doctrine/icd9dictionary/lookupICD9.php"
    $.post(requestURL,
        {
            searchString: problem,
            requestTime: requestTime,
            lookupType: "KEYWORD"
        },
        icd9results,
        "json"
    );    

    $.post(requestURL,
        {
            searchString: problem,
            requestTime: requestTime,
            lookupType: "CODES"
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