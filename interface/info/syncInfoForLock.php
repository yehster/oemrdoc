<?php
require_once("$doctrineroot/queries/AllergiesQueries.php");
require_once("$doctrineroot/common/EditorConstants.php");
require_once("$doctrineroot/Entities/EntryStatusCodes.php");
function findSection($items,$code,$code_type)
{
    if($items==null)
    {
        return null;
    }
    $itemsQueue=array();
    foreach($items as $item)
    {

        $entry=$item->getEntry();
        if(($entry->getType()==TYPE_SECTION) && ($entry->getCode()==$code)&& ($entry->getCode_type()==$code_type))
        {
            return $item;
        }
        else
        {
            foreach($item->getItems() as $subItem)
            {
                $itemsQueue[]=$subItem;            
            }
        }
    }
    if(count($itemsQueue)>0)
    {
            return findSection($itemsQueue,$code,$code_type);
    }
    else
    {
        return null;
    }
}
function syncInfoForLock($em,$doc,$user,$code,$code_type)
{
    
    $infoSection = findSection($doc->getItems(),$code,$code_type);
    if($infoSection==null)
    {
        echo "Oops";
        return;
    }
    // We need to find the active meds which are not already part of the current document.
        $missingInfo=findSubEntries($em,$doc->getPatient(),$code,$code_type,$doc);
    foreach($missingInfo as $info)
    {
        $copy=$info->copy($user);
        $newItem=$infoSection->addEntry($copy);
        $em->persist($copy);
        $copy->setStatus(STATUS_AUTO_ACTIVE);
    }
    
}
?>
