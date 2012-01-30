
    var KeyShortcutMap=new Array();
    KeyShortcutMap[112]="PATIENT";
    KeyShortcutMap[110]="NOTINDICATED";

function dispatchShortcut(textBox,keyCode)
{

    $.post("../../interface/shortcuts/getShortcut.php",
    {
        shortcut: KeyShortcutMap[keyCode],
        patientID: patID
    },
    function(data)
    {
            textBox.val(textBox.val()+$.trim(data));
    }
    );
}
function narrativeKeyPress(evt)
{
    if(evt.altKey)
        {
            dispatchShortcut($(this),evt.which)

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