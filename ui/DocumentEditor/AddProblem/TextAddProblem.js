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
            parent.find("input").before("<div func='probDisplay'></div>");
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
        registerTextAddProblemEvents();
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
function updateProblemList(html)
{
    display=$("div[func='probDisplay']");
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

}

function evtChangeTextAddProblem()
{
    display=setupProblemDisplay(this);
    luProblem($(this).val());
}

function registerTextAddProblemEvents()
{
    $("input[type='text'][func='ADDPROB']").keyup(evtChangeTextAddProblem);
}