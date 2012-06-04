<?php
require_once("manageParentEntry.php");
require_once("../analysis/analyzeProblems.php");

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
        $oli=updateOEMRProblem($prob);
        if(($oli->getBegDate()==null) || $oli->getBegDate()<new \DateTime)
        {
            $oli->setBegDate(new \DateTime);
        }

        if(isset($_REQUEST['addNarrative']) && ($_REQUEST['addNarrative']=="YES"))
        {
                    $newNarrative=new library\doctrine\Entities\Narrative(null, $pat, $user);
                    $newNarItem=$newItem->addEntry($newNarrative);
                    $em->persist($newNarrative);
        }
        $em->persist($prob);
        $em->flush();
        break;
}
require_once("refreshCheck.php");
?>
