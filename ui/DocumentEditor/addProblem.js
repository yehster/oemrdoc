/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function addProblem()
{
    entryUUID=$(this).attr("entryUUID");
    window.alert(entryUUID);
}

function registerAddProblemEvents()
{
    $("button[func='ADDPROB']").live({click: addProblem});
}