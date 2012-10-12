function waitForClick(selector)
{
    var element=$(selector);
    if(element.length>0)
    {
        element.click();
    }
    else
    {
        setTimeout("waitForClick('"+selector+"')",200);
    }
}

function updateInput(selector,value)
{
    $(selector).val(value);
    $(selector).keyup();
}

function test_select_group()
{
    waitForClick("#sectionResults tr:first td:first");
    waitForClick("td.groupDescription:first");
    updateInput("#codesLookup","diabetes");
}
function testInitialization()
{
    test_select_group();
}