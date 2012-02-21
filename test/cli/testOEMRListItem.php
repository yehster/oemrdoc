<?php
include('/var/www/openemr/library/doctrine/init-session.php');

$pat=$em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('pid' => 2));
$testItem = new library\doctrine\Entities\OEMRProblem($pat,"Obesity","278.02");
$testItem->setBegDate(new \DateTime());

$em->persist($testItem);
$em->flush();

?>
