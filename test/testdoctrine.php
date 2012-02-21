<?php

if (!empty($argc) && strstr($argv[0], basename(__FILE__))) {
}
else
{
    include('/var/www/openemr/interface/globals.php');
}

include('/var/www/openemr/library/doctrine/init-session.php');


//phpinfo();
//echo $cache;


echo "hello world";
$patient2= $em->getRepository('library\doctrine\Entities\Patient')->find(13);
$patient= $em->getRepository('library\doctrine\Entities\Patient')->findOneBy(array('fname' => 'First7991419'));


$patient->display();


//ReflectionObject::Export($patient->getDOB());

$patient2->display();


$patient3=new library\doctrine\Entities\Patient();
//$patient3->

$document = new library\doctrine\Entities\Document();
$documentitem = new library\doctrine\Entities\DocumentItem();
$documentchild = new library\doctrine\Entities\DocumentItem();
$documentEntry = new library\doctrine\Entities\Observation();

$document->addItem($documentitem);
$documentitem->setEntry($documentEntry);
$documentitem->addItem($documentchild);

$em->persist($document);
$documentEntry->setText('no acute distress','kyeh');
$documentEntry->setText('10210-3','LOINC');
$em->flush();
?>



