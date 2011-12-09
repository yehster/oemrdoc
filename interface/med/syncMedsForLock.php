<?php
@require_once("$doctrineroot/queries/MedicationQueries.php");
@require_once("$doctrineroot/common/EditorConstants.php");
function findMedSection($items)
{
    foreach($items as $item)
    {
        $entry=$item->getEntry();
        if(($entry->getType()==TYPE_SECTION) && ($entry->getText()==SECTION_MEDICATIONS))
        {
            return $item;
        }
        else
        {
            return findMedSection($item->getItems());
        }
    }
    return null;
}
function syncMedsForLock($em,$doc,$user)
{
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
