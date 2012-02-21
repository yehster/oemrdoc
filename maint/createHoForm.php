<?php
include('/var/www/openemr/library/doctrine/init-session.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'HO'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('HO','HO');
    $em->persist($form);
    $em->flush();
}





//Assessment and Plan: 51847-2
$ap = findOrCreateSection($em,'AP','Assessment/Plan');
$ap->setCode('51847-2','LOINC');

$apnar = findOrCreateNarrativeMetadata($em,'APNAR','Assessment/Plan Narrative');
$apnar->setCode('51847-2','LOINC');


findOrCreateMDCI($em,$ap,$form);
findOrCreateMDCI($em,$apnar,$ap);

// 29274-8 vital signs
//44100-6	Medical problem

$prob = findOrCreateSection($em,'PROB','Problem List');
findOrCreateMDCI($em,$prob,$ap);

$em->flush();

?>
