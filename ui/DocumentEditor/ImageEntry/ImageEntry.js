// Drawing Functions go below here
var canvas_states=new Array();
function updateCanvas(canvasUUID)
{
    canvas_state=canvas_states[$(this).attr("canvasUUID")];
    canvas_state.num++;
    canvas=$("canvas[canvasuuid='"+canvasUUID+"']");
    ShowStatusMessage(canvas_state.num+":"+canvas.length);
    canvas.get(0).getContext("2d").stroke();
}
function startDraw(e)
{
    canvas_state = new Object();
    canvas_state.drawing=true;
    canvas_state.num=0;
    mouseX = e.pageX-$(this).offset().left;
    mouseY = e.pageY-$(this).offset().top;
    canvasUUID=$(this).attr("canvasUUID");
    canvas_state.X=mouseX;
    canvas_state.Y=mouseY;
    canvas_state.strokeTimer=setInterval("updateCanvas('"+canvasUUID+"')",200);
    context = this.getContext("2d");
    canvas_states[$(this).attr("canvasUUID")]=canvas_state;
    context.beginPath();
    context.moveTo(mouseX,mouseY);
}

function contDraw(e)
{
    canvas_state=canvas_states[$(this).attr("canvasUUID")];
    if(canvas_state && canvas_state.drawing)
        {
            context = this.getContext("2d");
            mouseX = e.pageX-$(this).offset().left;
            mouseY = e.pageY-$(this).offset().top;
            context.lineTo(mouseX,mouseY);
        }

}
function endDraw(evt)
{
    canvas_state=canvas_states[$(this).attr("canvasUUID")];
    canvas_state.drawing=false;
    canvas_states[$(this).attr("canvasUUID")]=canvas_state;
            context = this.getContext("2d");
            context.stroke();
            clearInterval(canvas_state.strokeTimer);
}
function canvasEvents(parent)
{
    parent.find("canvas.image_entry").on(
    {
        mousedown: startDraw,
        mousemove: contDraw,
        mouseup: endDraw,
        mouseleave: endDraw
    });
}

// Drawing Functions go above here

function handleCreateImage(data)
{
    refreshEntry(data.uuid,data.html);
}
function clickImageButtonEvt()
{
    parentUUID=$(this).attr("entryuuid");
    $.post("../../interface/ImageEntry/manageImageEntry.php",
    {
        task: "CREATE",
        parentUUID: parentUUID,
        refresh: "YES"
    },
    handleCreateImage,
    "json"
    );
}


function registerImageEntryEvents(parent)
{
    if(parent==null)
        {
            parent=$(document);
        }
    parent.find("button[func='IMAGE']").click(clickImageButtonEvt);
    canvasEvents(parent);
}