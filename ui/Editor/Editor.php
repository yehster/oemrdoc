<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/Editor.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/ProblemManagement.js" type="text/javascript"></script>

<style type="text/css" media="all">
   @import "/openemr/library/doctrine/ui/Editor/Editor.css";
   @import "/openemr/library/doctrine/ui/Editor/Form.css";
   @import "/openemr/library/doctrine/ui/Editor/ProblemManagement.css";

</style>

<?php
//include_once('/var/www/openemr/interface/globals.php');
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorConstants.php');
include_once('/var/www/openemr/library/doctrine/ui/Editor/EditorUtilities.php');


if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        echo "Document:".$docUUID;
        return;
    }
    $EditorDOM = new DOMDocument("1.0","utf-8");
    $Body=$EditorDOM->createElement("BODY");
    $popupDIV=$EditorDOM->createElement("DIV", " ");
    $Body->appendChild($popupDIV);
    $popupDIV->setAttribute("ID",ID_POPUP);
    foreach($doc->getItems() as $docItem)
    {
        generateEditorDOM($EditorDOM,$Body,$docItem,1);
    }
}



?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></title>
    </head>
<?php echo $EditorDOM->saveXML($Body);?>
</html>