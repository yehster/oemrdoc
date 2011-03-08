<?php
include('/var/www/openemr/library/doctrine/init-em.php');

$pat=$em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('pid' => 2));

// Metadata is null for now.  Think about if it needs metadata
$prob = new library\doctrine\Entities\Problem(null,$pat,"admin");
$prob->setCode("278.02","ICD9");
$prob->setText("Overweight","admin");
$em->persist($prob);
$em->flush();

?>
