<?php



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
?>
