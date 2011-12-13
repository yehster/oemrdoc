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

    selector="[entrytype='Narrative']";
    if(parent==null)
        {
           toBind= $(selector);
        }
        else
        {

            toBind= parent.find("textarea");
        }
    toBind.keypress(narrativeKeyPress);
}