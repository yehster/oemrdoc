<script src="/openemr/library/js/jquery-1.5.min.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/Editor.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/ProblemManagement.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/MedManagement.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/FormNominatives.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/Review.js" type="text/javascript"></script>
<script src="/openemr/library/doctrine/ui/Editor/FormQuant.js" type="text/javascript"></script>


<style type="text/css" media="all">
   @import "/openemr/library/doctrine/ui/Editor/Editor.css";
   @import "/openemr/library/doctrine/ui/Editor/Form.css";
   @import "/openemr/library/doctrine/ui/Editor/ProblemManagement.css";
   @import "/openemr/library/doctrine/ui/Editor/MedManagement.css";
   @import "/openemr/library/doctrine/ui/Editor/Review.css";
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

    $reviewDIV=$EditorDOM->createElement("DIV", " ");
    $reviewDIV->setAttribute("ID",ID_REVIEW);
    $Body->appendChild($reviewDIV);

    $closeReview=$EditorDOM->createElement("BUTTON","close");
    $closeReview->setAttribute("class","CloseReview");
    
    $reviewDIV->appendChild($closeReview);

    $historyDIV=$EditorDOM->createElement("DIV", " ");
    $historyDIV->setAttribute("ID",ID_REVIEW_HISTORY);
    $reviewDIV->appendChild($historyDIV);

    $currentDIV=$EditorDOM->createElement("DIV", " ");
    $currentDIV->setAttribute("ID",ID_REVIEW_CURRENT);
    $reviewDIV->appendChild($currentDIV);




    foreach($doc->getItems() as $docItem)
    {
        generateEditorDOM($EditorDOM,$Body,$docItem,1);
    }
}



?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; utf-8">
        <title><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></title>
    </head>
    <div><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></div>
<?php echo $EditorDOM->saveXML($Body);?>
</html>