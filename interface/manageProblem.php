<?php

include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('../ui/Editor/EditorUtilities.php');


$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user set!";
    return;
}
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    
}
if(is_null($pat))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No patient specified!";
    return;
    
}

if(isset($_REQUEST["task"]))
{
    $task=$_REQUEST["task"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No task specified!";
    return;
}

if(isset($_REQUEST["parentUUID"]))
{
    $parentUUID = $_REQUEST["parentUUID"];
    $parentEntry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($parentUUID);
}

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
        echo $text;
        break;
}

?>
