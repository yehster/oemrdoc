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
        newSpan.mouseleave(hideProblemMenu)
    }
    return menu;
}
function menuClickProblem()
{
    menu=displayProblemMenu(this);
    menu.attr("style","position:absolute;")
//    $(this).hide();
}
function registerManageProblemEvents(parent)
{
    if(parent==null)
    {
        parent=$("body");
    }
    parent.find("span[entrytype='Problem'] button[func='MENU']").on(
        {
            click: menuClickProblem,
            mouseenter: menuClickProblem
        });
}