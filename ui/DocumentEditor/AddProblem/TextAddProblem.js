/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function setupProblemDisplay(textControl)
{
    parent=$(textControl).parent("span");
    retVal=parent.find("div[func='probDisplay']");
    if(retVal.length==0)
        {
            div=document.createElement("div");
            div.setAttribute("func","probDisplay");
            favorites=document.createElement("div");
            favorites.setAttribute("id","favoriteProblems");
            list=document.createElement("div");
            list.setAttribute("id","listProblems");
            div.appendChild(favorites);
            div.appendChild(list);
            parent.find("input").before(div);
            retVal=parent.find("div[func='probDisplay']");
        }
   retVal.show();
   return retVal;
}

function createProblem2(parentUUID,code,codeType,text)
{
    $.post("../../interface/manageProblem.php",
    {
        parentUUID: ""+parentUUID+"",
        code: ""+code+"",
        codeType: ""+codeType+"",
        text: ""+text+"",
        task: "create",
        refresh: "YES"
    },
    function(data)
    {
        refreshEntry(parentUUID,data);
    }
    );
}
function addProblem()
{
                    entryUUID=$(this).parents("[UUID]:first").attr("UUID");
                    code=$(this).attr("code");
                    codeType=$(this).attr("codetype")
                    text=$(this).text();
                    createProblem2(entryUUID,code,codeType,text);
                    display=$("div[func='probDisplay']");
                    display.hide();

}

function codeClick()
{
    window.alert($(this).text());
}

function closeProblemDiv()
{
    $("div[func='probDisplay']").hide();
}
function updateProblemList(html)
{
    display=$("#listProblems");
    display.html(html);

    display.find("tr[id] td[type='CODETEXT']").mouseover(function(){$(this).addClass("highlight")}).mouseout(function(){$(this).removeClass("highlight")}).click(addProblem);
    display.find("tr td.CODE").click(codeClick);
}
function updateFavoritesList(html)
{
    display=$("#favoriteProblems");
    display.html(html);

    display.find("tr[id] td[type='CODETEXT']").mouseover(function(){$(this).addClass("highlight")}).mouseout(function(){$(this).removeClass("highlight")}).click(addProblem);
    display.find("tr td.CODE").click(codeClick);
}
function luProblem(searchString)
{
        $.post("../Dictionary/lookupProblems.php",
        {
            searchString: ""+searchString+""
        },
        updateProblemList
        );
            $.post("../Dictionary/favoriteProblems.php",
        {
            searchString: ""+searchString+""
        },
        updateFavoritesList
        );
}

function evtChangeTextAddProblem()
{
    display=setupProblemDisplay(this);
    luProblem($(this).val());
}
function evtFocusTextAddProblem()
{
    if($(this).val().length>0)
        {
            display=$("div[func='probDisplay']").show();  
        }
        else
            {
                $(this).keyup();
            }
}
function hideProblemList()
{
    $("div[func='probDisplay']").hide();
}
function evtBlurTextAddProblem()
{
    setTimeout(hideProblemList,200);
}
function registerTextAddProblemEvents()
{
    $("input[type='text'][func='ADDPROB']").live(
            {
            keyup:evtChangeTextAddProblem,
            focus:evtFocusTextAddProblem,
            blur: evtBlurTextAddProblem});
}