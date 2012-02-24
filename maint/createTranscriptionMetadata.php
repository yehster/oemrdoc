<?php
include('/var/www/openemr/library/doctrine/init-session.php');
include('metadataMaint.php');

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'Trans'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('Trans','Transcription');
    $em->persist($form);
    $em->flush();
}


$trans= findOrCreateTranscriptionInfoMetadata($em,'TRSINFO','Transcription Info');
findOrCreateMDCI($em,$trans,$form);

$em->flush();
?>
