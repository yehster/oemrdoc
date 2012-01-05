function findOrCreateChangeOptions(control,uuid)
{
    menuSpan=$(control).parents("span.menuContainer");
    controlSpan=menuSpan.parent("[uuid]");
    problemList=controlSpan.find("section.changeOptions");
    if(problemList.length==0)
    {
        problemList=$("<section class='changeOptions'></section>").appendTo(controlSpan);
        problemDictionary=$("<section class=problemDictionary></section>").appendTo(problemList);
        problemList.attr("problemuuid",uuid);
        problemDictionary.attr("problemuuid",uuid);
    }
    return problemList;
    
}

function postUpdateProblem(code,codeType,text,uuid)
{
    $.post("../../interface/problem/changeProblem.php",
    {
        entryUUID: uuid,
        text: text,
        code: code,
        codeType: codeType,
        refresh: "YES"
    },
    function(data)
    {
        refreshEntry(data.uuid,data.html);
    },"json"
    
    );
    
}
function updateProblem()
{
    code=$(this).attr("code");
    codeType=$(this).attr("codetype")
    text=$(this).text();
    parentElem=$(this).parents(".changeOptions[problemUUID]");
    uuid=parentElem.attr("problemUUID");
    postUpdateProblem(code,codeType,text,uuid);
    parentElem.hide();
}

function updateChangeProblem(data,problemUUID)
{
    display=$(".problemDictionary[problemUUID='"+problemUUID+"']");
    loc=data.indexOf("|");
    reqTime=parseInt(data.substr(0,loc));
    displayData=data.substr(loc+1);
    prevReq=parseInt(display.attr("reqTime"));
    prevReq=isNaN(prevReq) ? 0 : prevReq;
    if(reqTime>=prevReq)
    {
        display.attr("reqTime",prevReq);
        display.html(displayData);

            display.find("tr[id] td[type='CODETEXT']").on({
                mouseover: function(){$(this).addClass("highlight")},
                mouseout:function(){$(this).removeClass("highlight")},
                click: updateProblem
            });
    
    }
}
function lookupChangeProblem(searchString,problemUUID)
{
    
        requestTime=new Date().getTime();
        changeLookupTimer=null
        $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+"",
            requestTime: requestTime
        },
        function(data)
        {
            updateChangeProblem(data,problemUUID);
        }
        );

}

var changeLookupTimer=null;
function keypressChangeProblem()
{
    problemUUID=$(this).attr("problemuuid");
    problemList=findOrCreateChangeOptions(this,problemUUID);
     if(changeLookupTimer!=null)
        {
            clearTimeout(changeLookupTimer);
        }
        problemList.show();
        statement="lookupChangeProblem('"+$(this).val()+"','"+problemUUID+"')";
    changeLookupTimer=setTimeout(statement,200);
}
function sortProblem()
{
    menuSpan=$(this).parents("span.menuContainer");
    button=menuSpan.find("button[func='MENU']");
    entryUUID=button.attr("entryUUID");
    task=$(this).attr("FUNC");
    $.post("../../interface/sorting.php",
    {
        entryUUID: entryUUID,
        task: task,
        refresh: "YES"
    },
        function(data)
        {
            uuid=data.uuid;
            refreshEntry(uuid,data.html);
        }
        ,"json"
    )
}
function createProblemMenuEntry(parent,display,func)
{
    tr=$("<tr></tr>").appendTo(parent);
    tr.attr("func",func);
    td=$("<td>"+display+"</td>").appendTo(tr);
    return tr;
}
function hideProblemMenu()
{
    $(this).find("button[func='MENU']").show();
    $(this).find("span.problemMenu").hide();
    uuid=$(this).find("[problemuuid]").attr("problemuuid");
    selector="section.changeOptions[problemUUID='"+uuid+"']";
    $(selector).hide();
    
}
function displayProblemMenu(target)
{
    parent=$(target).parent();
    menu=$(target).siblings("span.problemMenu");
    if(menu.length==0)
    {
        newSpan=$("<span class='menuContainer'></span>").insertAfter(target);
        $(target).appendTo(newSpan)
        
        menu=$("<span class='problemMenu'></span>").insertAfter(target);
        menu.attr("style","position:absolute;")
        table=$("<table class='problemMenu'><tbody></tbody></table>").appendTo(menu);
        tbody=table.find("tbody");
        up=createProblemMenuEntry(tbody,"Move Up","UP").click(sortProblem);
        down=createProblemMenuEntry(tbody,"Move Down","DOWN").click(sortProblem);
        change=createProblemMenuEntry(tbody,"Change Code","CHANGE");
        problemText=$("<input type='text' class='changeProblemCode'>").appendTo(change.find("td"));
        problemText.on({keyup: keypressChangeProblem});
        problemText.attr("problemuuid",parent.attr("uuid"));
        newSpan.mouseleave(hideProblemMenu)
    }
    return menu;
}
function menuClickProblem()
{
    menu=displayProblemMenu(this);
    menu.attr("style","position:absolute;")
    menu.find("input.changeProblemCode").focus();
//    $(this).hide();
}
function registerManageProblemEvents(parent)
{
    if(parent==null)
    {
        parent=$("body span[entrytype='Problem']");
    }
    parent.find("button[func='MENU']").on(
        {
            click: menuClickProblem,
            mouseenter: menuClickProblem
        });
}