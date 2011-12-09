<?php
@require_once("$doctrineroot/queries/MedicationQueries.php");
@require_once("$doctrineroot/common/EditorConstants.php");
function findMedSection($items)
{
    if($items==null)
    {
        return null;
    }
    $itemsQueue=array();
    foreach($items as $item)
    {
        $entry=$item->getEntry();
        echo $entry->getText();
        if(($entry->getType()==TYPE_SECTION) && ($entry->getText()==SECTION_MEDICATIONS))
        {
            return $item;
        }
        else
        {
            $itemsQueue[]=$item;
        }
    }
    if(count($itemsQueue)>0)
    {
            return findMedSection($itemsQueue);
    }
    else
    {
        return null;
    }
}
function syncMedsForLock($em,$doc,$user)
{
    echo count($doc->getItems());
    $medSection = findMedSection($doc->getItems());
    if($medSection==null)
    {
        echo "Oops";
        return;
    }
    // We need to find the active meds which are not already part of the current document.
    $missingMeds=findMedications($em,$doc->getPatient(),$doc);
    echo "Missing count=".count($missingMeds)."|";
    
}
?>
