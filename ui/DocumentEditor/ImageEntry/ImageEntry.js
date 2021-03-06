// Drawing Functions go below here
var canvas_states={};
function startDraw(e)
{
    var canvas_state = new Object();
    canvas_state.drawing=true;
    canvas_state.num=0;
    canvas_state.point_count=0;
    var mouseX = e.pageX-$(this).offset().left;
    var mouseY = e.pageY-$(this).offset().top;
    var canvasUUID=$(this).attr("canvasUUID");
    canvas_state.X=mouseX;
    canvas_state.Y=mouseY;
    var context = this.getContext("2d");
    canvas_states[canvasUUID]=canvas_state;
    context.beginPath();
    context.moveTo(mouseX,mouseY);
}

function contDraw(e)
{
    var canvas_state=canvas_states[$(this).attr("canvasUUID")];
    if(canvas_state && canvas_state.drawing)
        {
            context = this.getContext("2d");
            mouseX = e.pageX-$(this).offset().left;
            mouseY = e.pageY-$(this).offset().top;
            context.lineTo(mouseX,mouseY);
            canvas_state.point_count++;
            context.stroke();
    }

}
function endDraw(evt)
{
    var canvas_state=canvas_states[$(this).attr("canvasUUID")];
    if(canvas_state!=null)
        {
            canvas_state.drawing=false;    
            canvas_states[$(this).attr("canvasUUID")]=canvas_state;
        }
            var context = this.getContext("2d");
            context.stroke();
            evt.preventDefault();
            return false;
}
function canvasEvents(parent)
{
    parent.find("canvas.image_entry").on(
    {
        mousedown: startDraw,
        mousemove: contDraw,
        mouseup: endDraw,
        mouseleave: endDraw,
        touchstart: startDraw,
        touchmove: contDraw,
        touchend: endDraw
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