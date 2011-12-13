function narrativeKeyPress(evt)
{
    if(evt.altKey)
        {
            window.alert("yo");
        }
}

// parent should be a jQuery object
function registerNarrativeShortcuts(parent)
{

    selector="textarea[entrytype='Narrative']";
    if(parent==null)
        {
           toBind= $(selector);
        }
        else
        {
            window.alert("rebinding");
            toBind= parent.find(selector);
        }
    toBind.keypress(narrativeKeyPress);
}