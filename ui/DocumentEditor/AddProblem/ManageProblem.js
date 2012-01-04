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
    parent=$(target).parent("[entrytype='Problem']");
    menu=parent.find("span.problemMenu");
    if(menu.length==0)
    {
        newSpan=$("<span class='menuContainer'></span>").insertAfter(target);
        $(target).appendTo(newSpan)
        
        menu=$("<span class='problemMenu'></span>").insertAfter(target);
        table=$("<table class='problemMenu'><tbody></tbody></table>").appendTo(menu);
        tbody=table.find("tbody");
        up=createProblemMenuEntry(tbody,"Move Up","UP");
        down=createProblemMenuEntry(tbody,"Move Down","DOWN");
        change=createProblemMenuEntry(tbody,"Change Code","CHANGE");
        newSpan.mouseleave(hideProblemMenu)
    }
    return menu;
}
function menuClickProblem()
{
    menu=displayProblemMenu(this);
    $(this).hide();
    menu.show();
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