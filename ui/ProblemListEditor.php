<?php
include('/var/www/openemr/library/doctrine/init-em.php');

$HTMLDom = new DOMDocument("1.0", "utf-8");

if(isset($_REQUEST['sectionUUID']))
{
    $sectionUUID = $_REQUEST['sectionUUID'];
    $section = $em->getRepository('library\doctrine\Entities\Section')->find($sectionUUID);
}
        if($section!=null)
        {
            
        }
        else
        {
            echo "Section:".$sectionUUID;
            return;
        }
?>
<style type="text/css">
    .problemItem{
        font: normal;
        font-family: sans-serif;
        font-size: 10pt;
}
    .Highlight {
        background-color: yellow;
        cursor: pointer;
    }
</style>
<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script type="text/javascript">

function lookupProblem() {
      inputString=this.value;
    if(inputString.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("/openemr/library/doctrine/ui/dictionaryLookup.php", {searchString: ""+inputString+"",context: "code", className: "problemItem", maxRes: "5" }, function(data){
            if(data.length >0) {
                $('#divProblemSuggestions').show();
                $('#divProblemSuggestions').html(data);
            }
        });

    }
}

function addProblem() {
        code_text=$(this).find("td.problemItem").text();
        code = $(this).find("td.CODE").text();
        uuid = '<?php echo $sectionUUID?>';
        $.post("/openemr/library/doctrine/interface/manageProblem.php",{sectionUUID: ""+ uuid + "",code: ""+code+"",content: ""+code_text+"", task: 'create'}, function(data){ window.alert(data);});

}

function setupEvents()
{
    $("#txtNewProblem").keyup(lookupProblem);
    $("tr.problemItem").live({mouseover: function() {$(this).addClass('Highlight');} ,mouseout: function() {$(this).removeClass('Highlight');}, click: addProblem});

}
window.onload= setupEvents;
</script>



<?php
$addProblemDiv=$HTMLDom->createElement("DIV");
$HTMLDom->appendChild($addProblemDiv);

$addProblemDiv->setAttribute("name","addProblem");

$addProblemText=$HTMLDom->createElement("INPUT");
$addProblemText->setAttribute("TYPE","text");

$addProblemText->setAttribute("ID","txtNewProblem");
$addProblemText->setAttribute("CLASS","problemItem");

$probSuggestions=$HTMLDom->createElement("DIV");
$probSuggestions->setAttribute("ID","divProblemSuggestions");

$addProblemDiv->appendChild($addProblemText);
$addProblemDiv->appendChild($probSuggestions);

echo $HTMLDom->saveXML();
?>

