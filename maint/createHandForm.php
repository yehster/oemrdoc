<?php
include('/var/www/openemr/library/doctrine/init-em.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'HAND'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('Hand','Hand Evaluation');
    $em->persist($form);
    $em->flush();
}


$imp = findOrCreateSection($em,'IMPR','Impression');
findOrCreateMDCI($em,$imp,$form);

$plan = findOrCreateSection($em,'PLAN','Plan');
findOrCreateMDCI($em,$plan,$form);

$plannar = findOrCreateNarrativeMetadata($em,'PLANNAR','Plan Narrative');
findOrCreateMDCI($em,$plannar,$plan);


$int = findOrCreateSection($em,'INT','Interval History');
$intnar = findOrCreateNarrativeMetadata($em,'INTNAR','Interval History Narrative');

findOrCreateMDCI($em,$int,$form);
findOrCreateMDCI($em,$intnar,$int);

$med = findOrCreateSection($em,'MED','Medications');
findOrCreateMDCI($em,$med,$form);
$med->setCode('52471-0',"LOINC");




$pe = findOrCreateSection($em,'PE','Physical Exam');
$pe->setCode("22029-3","LOINC");
findOrCreateMDCI($em,$pe,$form);



$xray = findOrCreateSection($em,'XRAY','X-rays');
findOrCreateMDCI($em,$xray,$form);

$xraynar = findOrCreateNarrativeMetadata($em,'XRAYNAR','X-Rays Narrative');
findOrCreateMDCI($em,$xraynar,$xray);

$inj = findOrCreateSection($em,'INJ','Injections');
findOrCreateMDCI($em,$inj,$form);

$injnar = findOrCreateNarrativeMetadata($em,'INJNAR','Injections Narrative');
findOrCreateMDCI($em,$injnar,$inj);


$cast = findOrCreateSection($em,'CAST','Casting/Splinting');
findOrCreateMDCI($em,$cast,$form);

$castnar = findOrCreateNarrativeMetadata($em,'CASTNAR','Casting/Splinting Narrative');
findOrCreateMDCI($em,$castnar,$cast);

$duty = findOrCreateSection($em,'DUTY','Duty Status');
findOrCreateMDCI($em,$duty,$form);
$dutynar = findOrCreateNarrativeMetadata($em,'DUTYNAR','Duty Status Narrative');
findOrCreateMDCI($em,$dutynar,$duty);

$billing = findOrCreateSection($em,'BILL','Billing');
findOrCreateMDCI($em,$billing,$form);

$em->flush();
?>
