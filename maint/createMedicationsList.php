<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'ML'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('ML','Medications List');
    $em->persist($form);
    $em->flush();
}

$MED= findOrCreateSection($em,'MED','Medications','52471-0','LOINC');
findOrCreateMDCI($em,$MED,$form);

?>
