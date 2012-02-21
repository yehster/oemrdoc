<?php
include('/var/www/openemr/library/doctrine/init-session.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'VS'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('VS','Vital Signs');
    $em->persist($form);
    $em->flush();
}

$VIT= findOrCreateSection($em,'VIT','Vital Signs','34565-2','LOINC');
findOrCreateMDCI($em,$VIT,$form);

?>
