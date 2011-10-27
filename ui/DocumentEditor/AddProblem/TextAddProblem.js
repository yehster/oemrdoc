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
   width= $(document).width() - $(parent).width()-$(parent).offset().left - 50;
   retVal.attr("computedWidth",width);
   retVal.width(width);
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

function setProblemDisplayWidth()
{
    display=$("div[func='probDisplay']");
//    window.alert(display.attr("computedWidth"));
    display.width(parseInt(display.attr("computedWidth")));
}

function closeProblemDiv()
{
    $("div[func='probDisplay']").hide();
}
function updateProblemList(html)
{
    display=$("#listProblems");
    display.html(html);
    setProblemDisplayWidth();
    
    display.find("tr[id] td[type='CODETEXT']").mouseover(function(){$(this).addClass("highlight")}).mouseout(function(){$(this).removeClass("highlight")}).click(addProblem);
    display.find("tr td.CODE").click(codeClick);
}
function updateFavoritesList(html)
{
    display=$("#favoriteProblems");
    display.html(html);
    setProblemDisplayWidth();

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

function evtButAddProblem()
{
    entryUUID=$(this).parents("[UUID]:first").attr("UUID");
    code="";
    codeType="";
    text=$("input[type='text'][func='ADDPROB']").val();
    createProblem2(entryUUID,code,codeType,text);
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
    $("button[func='ADDPROB']").live(
        {
            click:evtButAddProblem
        }
    )
}