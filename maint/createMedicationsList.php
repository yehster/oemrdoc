<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'MR'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('MR','Medications Review');
    $em->persist($form);
    $em->flush();
}

$MED= findOrCreateSection($em,'MED','Medications','52471-0','LOINC');
findOrCreateMDCI($em,$MED,$form);

$all = findOrCreateSection($em,'ALL','Allergies');
$all->setCode("8658-7","LOINC");
findOrCreateMDCI($em,$all,$form);

$drugAllergy=findOrCreateSubsection($em,"ALL:DRUG","Drug Allergy",$all);
$drugAllergy->setCode('A8380263','SNOMED');


$foodAllergy=findOrCreateSubsection($em,"ALL:FOOD","Food Allergy",$all);
$foodAllergy->setCode('A7873398',"SNOMED");

?>
