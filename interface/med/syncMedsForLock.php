<?php
require_once("$doctrineroot/queries/MedicationQueries.php");
require_once("$doctrineroot/common/EditorConstants.php");
require_once("$doctrineroot/Entities/EntryStatusCodes.php");

function findMedSection($items)
{
    if(count($items)==0)
    {
        return null;
    }
    $itemsQueue=array();
    foreach($items as $item)
    {
        $entry=$item->getEntry();
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
        $next_level_items=array();
        foreach($itemsQueue as $item)
        {
            foreach($item->getItems() as $subitem)
            {
                $next_level_items[]=$subitem;
            }
        }
            return findMedSection($next_level_items);
    }
    else
    {
        return null;
    }
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
    foreach($missingMeds as $med)
    {
        $copy=$med->copy($user);
        $newItem=$medSection->addEntry($copy);
        $em->persist($copy);
        $copy->setStatus(STATUS_AUTO_ACTIVE);
        foreach($med->getItem()->getItems() as $subItem)
        {

            $subCopy=$subItem->getEntry()->copy($user);
            $em->persist($subCopy);
            $newItem->addEntry($subCopy);
        }
    }
    
}
?>
