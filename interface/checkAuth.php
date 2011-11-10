<?php



$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user set!";
    return;
}
$doctrineUser=$em->getRepository('library\doctrine\Entities\User')->findOneBy(array("username"=>$user));

if($doctrineUser==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Invalid User!";
    return;    
}

if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    
}
if(!isset($pat))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No patient specified!";
    return;
    
}
?>
