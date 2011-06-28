<?php
require_once("manageParentEntry.php");

if(isset($_REQUEST["code"]))
{
    $code = $_REQUEST["code"];
}

if(isset($_REQUEST["codeType"]))
{
    $codeType = $_REQUEST["codeType"];
    if($codeType=="2")
    {
        $codeTypeString="ICD9";
    }
}

if(isset($_REQUEST["text"]))
{
    $text = $_REQUEST["text"];
}

switch ($task)
{
    case "create":
        if(is_null($parentEntry))
        {
                header("HTTP/1.0 403 Forbidden");
                echo "No parent specified on create!";
                return;
        }
        $prob = new library\doctrine\Entities\Problem(null,$pat,$user);
        $prob->setCode($code,$codeTypeString);
        $prob->setText($text,$user);
        $newItem=$parentEntry->getItem()->addEntry($prob);
        $em->persist($prob);
        $em->flush();

        
        break;
}

if(isset($_REQUEST['refresh']))
{
    $request=$_REQUEST['refresh'];
    if($request==="YES")
    {
        require_once("../ui/DocumentEditor/refreshSection.php");
        refreshSection($parentEntry->getItem());
        return;
    }
}
?>
