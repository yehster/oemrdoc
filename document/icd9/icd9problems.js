function closeicd9()
{
    var displayDiv  =$(this).parents(".icd9:first");
    displayDiv.hide();
}

function showicd9tab()
{
    var result_type=$(this).attr("result_type");
    var displayDiv  =$(this).parents(".icd9:first");
    icd9tabsvisibility(displayDiv,result_type);
}

function icd9tabsvisibility(displayDiv,result_type)
{
    var rt_selector="[result_type='"+result_type+"']";
    var tab=displayDiv.children("div"+rt_selector);
    tab.show();
    tab.siblings("div[result_type]").hide();
    var control=displayDiv.find("span"+rt_selector);
    control.addClass("type_selected");
    control.siblings("span[result_type]").removeClass("type_selected");
    
}
function displayicd9results(data)
{
    var table=data.codes;
    var type=data.type;
    var parent=$(problem_source).parent(".problemInput");
    var location=parent.find(".icd9");
    location.show();
    if(location.length==0)
        {
            location = $("<div></div>");
            location.addClass("icd9");
            location.attr("id","icd9");
            location.appendTo(parent);
            var controls=$("<div></div>");
            controls.addClass("icd9controls");
            location.append(controls);

            var type_tabs=$("<span></span>");
            type_tabs.addClass('icd9tabs');
            controls.append(type_tabs);
            
            var close=$("<span>&#x2713</span>")
            close.addClass("icd9_iconic");
            close.addClass("icd9_close");
            close.on({click: closeicd9 });
            controls.append(close);
        }
   var section=location.find("div[result_type='"+type+"']");
   var code_tab;
   if(section.length==0)
       {
           section = $("<div></div>");
           section.attr("result_type",type);
           var tabs =location.find(".icd9tabs");
           if(type=="CODES")
               {
                   location.find("div.icd9controls").after(section);
                   code_tab=$("<span>"+"Codes"+"</span>");
                   tabs.prepend(code_tab);
               }
           else
               {
                   section.appendTo(location);           
                   code_tab=$("<span>"+"Keywords"+"</span>");
                   tabs.append(code_tab);
               }
          code_tab.addClass("icd9typeselector");
          code_tab.attr("result_type",type);
          code_tab.on({click: showicd9tab});
       }
   var requestTime=parseInt(section.attr("requestTime"));
   var dataTime=parseInt(data.requestTime);
   if(isNaN(requestTime)||requestTime<=dataTime)
       {
           section.attr("requestTime",dataTime);
           section.html(table);
       }
//   location.find("td[defs]").append("<button>...</button>");
    bind_problem_table_events(section);
    var topLevelRows=section.find("tr[depth='1']:not([type='SP']):first");
    if(topLevelRows.length==1)
        {
            var codeNum=topLevelRows.children(".codeNum").text();
            var children=topLevelRows.parent().children("[parent_code='"+codeNum+"']").show();
            
        }
    if(type=="CODES")
        {
            if(section.find("tr").length==0)
                {
                    icd9tabsvisibility(location,"KEYWORD");
                }
                else
                {
                    var keywords=location.find("div[result_type='KEYWORD']");
                    var kw_rt=parseInt(keywords.attr("requesttime"));
                    if(dataTime>kw_rt)
                        {
                            icd9tabsvisibility(location,"CODES");
                        }
                    
                }
        }
        else
            {
            if(section.find("tr").length==0)
                {
                    icd9tabsvisibility(location,"CODES");
                }
                
            }
}
function add_problem(evt)
{
    var di=new doctrineInfo($(problem_source).parents("['uuid']:first"));
    var parentUUID=di.uuid;
    var text=$(this).text();
    var code=$(this).siblings("td.codeNum").text();
    var requestTime=new Date().getTime();
    var kw_row=$(this).parent("[keywords]");
    var keywords;
    if(kw_row.length>0)
        {
           keywords = kw_row.attr("keywords");    
        }
        else
            {
                keywords="";
            }
    debugMessage(parentUUID+":"+text+":"+code+":"+keywords);
    $.post("/openemr/library/doctrine/interface/manageProblem.php",
    {
        parentUUID: parentUUID,
        code: code,
        codeType: 2,
        text: text,
        task: "create",
        refresh: "doc",
        addNarrative: "YES",
        keywords: keywords,
        requestTime: requestTime
    },
    function(data)
    {
        refreshSection(data);
    },
    "json"
    );    
    $.post("/openemr/library/doctrine/interface/problem/manageFrequencies.php",
    {
        code: code,
        codeType: 2,
        keywords: keywords,
    },
    function(data)
    {
    },
    "json"
    );    

}
function show_parent_info(evt)
{
    var row;
    var searchType;
    row=$(this).parent("tr");
    var codeNum=row.children(".codeNum").text();
    if($(this).is(".codeDesc"))
    {
        searchType="children";
    }
    else
    {
        searchType="parent"
        if((typeof row.attr("parent_code")!="undefined"))
        {
            codeNum=row.attr("parent_code");
        }
    }
    var children=row.parent().children("[parent_code='"+codeNum+"']").show();
    if((children.length==0)||(searchType=="parent"))
        {
            debugMessage("Need to Lookup children");
            lookup_problem(codeNum,searchType);
        }
}
function bind_problem_table_events(parent)
{
    
    parent.find("tr[type='SP'] td.codeDesc").on(
    {
        click: add_problem
    });
    parent.find("tr[type='NS'] > td.codeDesc").on(
    {   click: show_parent_info}
    );
    parent.find("tr[type='Section'] > td.codeDesc").on(
    {   click: show_parent_info}
    );
        
    parent.find("tr[type] > td.codeNum").on(
    {   click: show_parent_info}
    );        
}
function icd9results(data)
{
        displayicd9results(data);    

}

function lookup_problem(problem,searchType)
{
    var requestTime=new Date().getTime();
    var requestURL="/openemr/library/doctrine/icd9dictionary/lookupICD9.php"
    if(searchType!="")
        {
            $.post(requestURL,
                {
                    searchString: problem,
                    requestTime: requestTime,
                    lookupType: "CODES",
                    searchType: searchType
                },
                icd9results,
                "json"
            );    
            
        }
        else
        {
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

}
function process_problems()
{
    var problem = $(this).val();
    if(typeof(lookup_timer)!='undefined')
    {
        clearTimeout(lookup_timer);
    }
    var lookup_code="lookup_problem(\'"+problem+"\',false)";
//    lookup_problem(problem);
    problem_source=this;
    lookup_timer=setTimeout(lookup_code,100);
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
        keyup: process_problems,
        focus: process_problems,
        blur: function()
        {
//            setTimeout("$('div.icd9').hide();",100);
        }
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
    // put each problem in an ordered list 
    var content=problemsLabel.siblings("span.content");
    var list=$("<ol></ol>");
    content.children().each(function(idx,elem)
    {
        var li=$("<li></li>");
        li.addClass("problem");
        li.append(elem);
        list.append(li);
    }
    );
    content.append(list);       
}