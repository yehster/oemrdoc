<?php
include('/var/www/openemr/library/doctrine/init-em.php');

function findOrCreateSubsection($em,$sd,$ld,$par,$code="",$codeType="")
{
    $retval = findOrCreateSection($em,$sd,$ld);
    if($code!="")
    {
        $retval->setCode($code,$codeType);
    }
    findOrCreateMDCI($em,$retval,$par);
    return $reval;

}

function findOrCreateSection($em,$sd,$ld)
{
    $sect = $em->getRepository('library\doctrine\Entities\SectionHeading')->findOneBy(array('shortDesc' => $sd));
    if($sect == null)
    {
        $sect = new library\doctrine\Entities\SectionHeading($sd,$ld);
        $em->persist($sect);
        $em->flush();
    }
    return $sect;
}

function findOrCreateNarrativeMetadata($em,$sd,$ld)
{
    $sect = $em->getRepository('library\doctrine\Entities\NarrativeMetadata')->findOneBy(array('shortDesc' => $sd));
    if($sect == null)
    {
        $sect = new library\doctrine\Entities\NarrativeMetadata($sd,$ld);
        $em->persist($sect);
        $em->flush();
    }
    return $sect;
}




function findOrCreateMDCI($em,$md,$parent)
{
//    $mdci = $em->getRepository('library\doctrine\Entities\DocumentMetadataCollectionItem')->findOneBy(array('parent'=> $parent->getuuid(), 'metadata' => $md));
    $qryString = "SELECT mdci FROM library\doctrine\Entities\DocumentMetadataCollectionItem mdci JOIN mdci.parent p JOIN mdci.metadata md WHERE p.shortDesc = '".$parent->getshortDesc()."' AND md.shortDesc='".$md->getshortDesc()."'";
    echo $qryString.PHP_EOL;
    $query = $em->createQuery($qryString);
    $mdci = $query->getResult();
    
    if($mdci == NULL)
    {
        $mdci=new library\doctrine\Entities\DocumentMetadataCollectionItem($md);
        $parent->addItem($mdci);
    }
    return $mdci;
}

$form = $em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('shortDesc' => 'HP'));
//$form->$items->delete();
if($form == NULL)
{
    $form = new library\doctrine\Entities\DocumentType('HP','History and Physical');
    $em->persist($form);
    $em->flush();
}

$cc = findOrCreateSection($em,'CC','Chief Complaint');
$ccnar = findOrCreateNarrativeMetadata($em,'CCNAR','Chief Complaint Narrative');
$ccnar->setCode("10154-3","LOINC");

findOrCreateMDCI($em,$cc,$form);
findOrCreateMDCI($em,$ccnar,$cc);



$hpi = findOrCreateSection($em,'HPI','History of Present Illness');
$hpinar = findOrCreateNarrativeMetadata($em,'HPINAR','History of Present Illness Narrative');
$hpinar->setCode('10164-2','LOINC');


findOrCreateMDCI($em,$hpi,$form);
findOrCreateMDCI($em,$hpinar,$hpi);

$pm = findOrCreateSection($em,'PMH','Past Medical History');
findOrCreateMDCI($em,$pm,$form);

$all = findOrCreateSection($em,'ALL','Allergies');
findOrCreateMDCI($em,$all,$form);

$med = findOrCreateSection($em,'MED','Medications');
findOrCreateMDCI($em,$med,$form);

$fam = findOrCreateSection($em,'FAM','Family History');
findOrCreateMDCI($em,$fam,$form);

$soc = findOrCreateSection($em,'SOC','Social History');
findOrCreateMDCI($em,$soc,$form);

$ros = findOrCreateSection($em,'ROS','Review of Systems');
$ros->setCode('10187-3','LOINC');
findOrCreateMDCI($em,$ros,$form);


$rosCON = findOrCreateSubsection($em,'ROS:CON','Review of Systems:Constitutional',$ros);
$rosEYE = findOrCreateSubsection($em,'ROS:EYE','Review of Systems:Eyes',$ros);
$rosENT = findOrCreateSubsection($em,'ROS:ENT','Review of Systems:Ears & Nose & Mouth & Throat',$ros);
$rosCV = findOrCreateSubsection($em,'ROS:CV','Review of Systems:Cardiovascular',$ros);
$rosRES = findOrCreateSubsection($em,'ROS:RES','Review of Systems:Respiratory',$ros);
$rosGI = findOrCreateSubsection($em,'ROS:GI','Review of Systems:Gastrointestinal',$ros);
$rosGU = findOrCreateSubsection($em,'ROS:GU','Review of Systems:Genitourinary tract',$ros);




$pe = findOrCreateSection($em,'PE','Physical Exam');
findOrCreateMDCI($em,$pe,$form);

//Physical findings of General appearance
$peGEN = findOrCreateSubsection($em,'PE:GEN','Physical Exam:General',$pe,'32434-3','LOINC');

// Physical findings of Head
$peHEAD = findOrCreateSubsection($em,'PE:HEAD','Physical Exam:Head',$pe,'10199-8','LOINC');

//Physical findings of Eye
$peEYE = findOrCreateSubsection($em,'PE:EYE','Physical Exam:Eyes',$pe,'10197-2','LOINC');

//Physical findings of Ears & Nose & Mouth & Throat
$peENT = findOrCreateSubsection($em,'PE:ENT','Physical Exam:Ears & Nose & Mouth & Throat',$pe,'11393-6','LOINC');

//Physical findings of Cardiovascular system
$peCV = findOrCreateSubsection($em,'PE:CV','Physical Exam:Cardiovascular system',$pe,'11421-5','LOINC');

//Physical findings of Respiratory system
$peRES = findOrCreateSubsection($em,'PE:RES','Physical Exam:Respiratory system',$pe,'11412-4','LOINC');

//11430-6
//Physical findings of Gastrointestinal system
$peGI = findOrCreateSubsection($em,'PE:GI','Physical Exam:Gastrointestinal system',$pe,'11430-6','LOINC');

//Physical findings of Musculoskeletal system 11410-8
$peMS = findOrCreateSubsection($em,'PE:MS','Physical Exam:Musculoskeletal system',$pe,'11430-6','LOINC');

//Physical findings of Extremities
$peEXT = findOrCreateSubsection($em,'PE:EXT','Physical Exam:Extremities',$pe,'10196-4','LOINC');

//Physical findings of Nervous system
$peNER = findOrCreateSubsection($em,'PE:NER','Physical Exam:Nervous system',$pe,'10202-0','LOINC');

// Physical findings of Skin 10206-1
$peSKIN = findOrCreateSubsection($em,'PE:SKIN','Physical Exam:Skin',$pe,'10206-1','LOINC');

// Physical findings of Lymph node 32450-9
$peLYMPH = findOrCreateSubsection($em,'PE:LYMPH','Physical Exam:Lymphatics',$pe,'10206-1','LOINC');

// Physical findings of Genitourinary tract 10198-0
$peGU = findOrCreateSubsection($em,'PE:GU','Physical Exam:Genitourinary tract',$pe,'10198-0','LOINC');


// Psychiatric findings Observed 11451-2
$pePSYCH = findOrCreateSubsection($em,'PE:PSYCH','Physical Exam:Psychiatric findings Observed',$pe,'11451-2','LOINC');



$stu = findOrCreateSection($em,'STU','Studies');
findOrCreateMDCI($em,$stu,$form);

//Assessment and Plan: 51847-2
$ap = findOrCreateSection($em,'AP','Assessment/Plan');
$ap->setCode('51847-2','LOINC');

$apnar = findOrCreateNarrativeMetadata($em,'APNAR','Assessment/Plan Narrative');
$apnar->setCode('51847-2','LOINC');


findOrCreateMDCI($em,$ap,$form);
findOrCreateMDCI($em,$apnar,$ap);


//44100-6	Medical problem

$prob = findOrCreateSection($em,'PROB','Problem List');
findOrCreateMDCI($em,$prob,$ap);

$em->flush();
?>
