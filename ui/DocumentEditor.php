<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include_once('DocumentUtilities.php');
/* This page when passed a document uuid will load the document and provide the
 * user interface for editing
*/






// Create the DOM
        if(isset($_REQUEST['docUUID']))
        {
                $docUUID = $_REQUEST['docUUID'];
                $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
        }
        if($doc==null)
        {
            header("HTTP/1.0 403 Forbidden");    
            echo "Document:".$docUUID. " not found";
            return;
        }
        $EditorDOM = new DOMDocument("1.0","utf-8");
        foreach($doc->getItems() as $docItem)
        {
            generateDOM($EditorDOM,$docItem);
        }
        $problemListSection = findDiv($EditorDOM,"Problem List");
        $problemSectionUUID = $problemListSection->getAttribute("ID");
?>

<style type="text/css">
    .Narrative {
        position: relative;
        font-family:sans-serif;
        left: 5px;
        font-size:small;
        overflow: auto;
    }
    .Section {
        color: blue;
        position: relative;
        left: 10px;
        font-family:sans-serif;
        font-size:small;
    }
    .Observation
    {
        position: relative;
        left: 5px;
        color: red;
    }
    .Highlight {
        background-color: yellow;
        cursor: pointer;
    }
    .problemItem{
        font-size:x-small;
    }
    .SpecifiedDrug{
        position: relative;
        left: 10px;
        font-size:small;
    }
    #popupDiv{
        position: fixed;
        top:5px;
        right: 10px;
        width: 75%;
        height: 95%;
        background-color:#FFFFFF;
        border:1px solid #999999;
        font-family: sans-serif;
        font-size:small;
        z-index: 1000;
        overflow: auto;
    }

    table.ObservationTable {
        border: 1px solid;
        border-collapse: collapse;
        font-family:sans-serif;
        font-size:small;
        width: 100%;
    }

        th.ObservationTable {
        border: 1px solid;
        background-color: lightblue;
        font-size: medium;
}
    td.ObservationTable {
        border: 1px solid;
    }

    .normal {
            background-color: lightgreen;
    }

    .abnormal {
            background-color: lightpink;
    }

    th.formHeader{
        width: 80%;
    }
    .FormSection {
            float: left;
            width: 32%;
            margin: 1px;
    }
    input.RB {
        width: 100%;
    }


</style>


<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/FormsScripts.js" type="text/javascript"></script>
<script type="text/javascript">


function clickDrug()
{
    rxcui=$(this).attr("rxcui");
    rxaui=$(this).attr("rxaui");
    tty=$(this).attr("tty");
    sourceSectionID=getAttrForElem(this,"problemsection");

    drugStr=$(this).text();
    textArea=$("#"+sourceSectionID).find(".ProblemEntry");

    if((tty=="SCD") || (tty=="SBD"))
    {
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
        {parentEntryUUID: ""+ sourceSectionID + "",rxcui: ""+rxcui+"",rxaui: ""+rxaui+"",content: ""+drugStr+"", task: 'create', EntryType: 'med', refresh: 'YES'},
        function(data){
        if(data.indexOf("error:",0)==-1)
                    {
                        $('#popupDiv').hide();
                        $('#popupDiv').html("");
                        idText = "#" + sourceSectionID;
                        $(idText).replaceWith(data) ;
                    }
                    else
                    {
                        window.alert(data);
                    }
      });

    }
    else if((tty=="SCDF") || (tty=="SBDF"))
    {
        textArea.val(drugStr);
    }
    else
    {
        textArea.val(drugStr);
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {rxcui: ""+rxcui+"",rxaui: ""+rxaui+"",context: "medSemantic", className: "medList", maxRes: "20" }, function(data){
            if(data.length >0) {
                $('#popupDiv').show();
                $('#popupDiv').html(data);
                }});
    }
}

function showStatus(text)
{
$('#status').html(text);
}
    function saveNarrative(evt)
    {
        value = this.value;
        id = this.id;
        $.post("/openemr/library/doctrine/interface/saveDocumentEntry.php",
            {docEntryUUID: ""+id+"",docEntryData:""+value+""},
                function(data){
                    if(data.length >0) {
                        showStatus(id +":"+ value+":"+data);
                    }
                    else
                    {
                        showStatus("updated:"+value);
                    }
            });
    }
    function showStatus(text)
    {
        $('#status').html(text);
    }

function lookupProblem() {
      inputString=this.value;
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $('#popupDiv').hide();
    } else {
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "code", className: "problemItem", maxRes: "5" }, function(data){
            if(data.length >0) {
                $('#popupDiv').show();
                $('#popupDiv').html(data);
            }
        });
    }
}
function keyupProblemEntry() {
    inputString=this.value;
    sectionID=$(this).attr("name");
    if(inputString.length==0)
    {
     $('#popupDiv').hide();
    }
    else
    {
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "med", className: "medList", maxRes: "20" }, function(data){
            if(data.length >0) {
                $('#popupDiv').show();
                $('#popupDiv').html(data);
                $('#popupDiv').attr("ProblemSection",sectionID);
                }
        })
    }

}
function refreshSection(id)
{
    $.post("/openemr/library/doctrine/ui/refreshDocumentEntry.php",{docEntryUUID:""+id+""},function (data){
       idText = "#" + id;
       $(idText).replaceWith(data) ;
    });
}
function appendToList(listItemID,section)
{
    $.post("/openemr/library/doctrine/ui/refreshDocumentEntry.php",{docEntryUUID:""+listItemID+""},function (data){
       sectionID = "#" + section;
       $(sectionID).find("OL").append(data) ;
    });

}
function addProblem() {
        code_text=$(this).find("td.problemItem").text();
        code = $(this).find("td.CODE").text();
        uuid = '<?php echo $problemSectionUUID?>';
        $.post("/openemr/library/doctrine/interface/manageEntry.php",
        {   parentEntryUUID: ""+ uuid + "",
            EntryType: "problem",
            code: ""+code+"",
            content: ""+code_text+"",
            task: 'create',
            refresh: "ENTRY"},
        function(data){
        if(data.indexOf("error:",0)==-1)
                    {
                        // successful
                        $("#txtNewProblem").val("");
                        $('#popupDiv').html("");
                        $('#popupDiv').hide();
                        sectionID = "#" + uuid;
                        $(sectionID).find("OL").append(data) ;
                    }
                    else
                    {
                        window.alert(data);
                    }
            });

}

    function registerControlEvents()
    {
        $(".Narrative").live({blur: saveNarrative});
        $("#txtNewProblem").live({keyup: lookupProblem});
        $("tr.problemItem").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: addProblem});
        $("TEXTAREA.ProblemEntry").live({keyup: keyupProblemEntry});
        $("td.medName").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: clickDrug});
        $("div.pe").live({click: displayForm})
        $("div.ros").live({click: displayForm})
        $("#finishPE").live({click: finishPhysicalExam})

        $("#popupDiv").hide();
        registerFormEvents();
    }
    window.onload= registerControlEvents;

</script>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <div id="popupDiv"></div>
        <?php echo $EditorDOM->saveXML($EditorDOM);  ?>

        <div id="status"></div>
    </body>
</html>