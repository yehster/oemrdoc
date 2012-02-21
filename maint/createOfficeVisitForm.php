<?php
include('/var/www/openemr/library/doctrine/init-session.php');
include('metadataMaint.php');

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
$cc->setCode("10154-3","LOINC");
$ccnar->setCode("10154-3","LOINC");

findOrCreateMDCI($em,$cc,$form);
findOrCreateMDCI($em,$ccnar,$cc);



$hpi = findOrCreateSection($em,'HPI','History of Present Illness');
$hpinar = findOrCreateNarrativeMetadata($em,'HPINAR','History of Present Illness Narrative');
$hpi->setCode('10164-2','LOINC');
$hpinar->setCode('10164-2','LOINC');


findOrCreateMDCI($em,$hpi,$form);
findOrCreateMDCI($em,$hpinar,$hpi);

$pm = findOrCreateSection($em,'PMH','Past Medical History');
$pm->setCode("11348-0","LOINC");
findOrCreateMDCI($em,$pm,$form);

$all = findOrCreateSection($em,'ALL','Allergies');
$all->setCode("8658-7","LOINC");
findOrCreateMDCI($em,$all,$form);

$drugAllergy=findOrCreateSubsection($em,"ALL:DRUG","Drug Allergy",$all);
$drugAllergy->setCode('A8380263','SNOMED');


$foodAllergy=findOrCreateSubsection($em,"ALL:FOOD","Food Allergy",$all);
$foodAllergy->setCode('A7873398',"SNOMED");

//$envAllergy=findOrCreateSubsection($em,"ALL:ENV","Environmental Allergy",$all);
//$envAllergy->setCode('A13016313',"SNOMED");

$med = findOrCreateSection($em,'MED','Medications');
findOrCreateMDCI($em,$med,$form);
$med->setCode('52471-0',"LOINC");

$fam = findOrCreateSection($em,'FAM','Family History');
$fam->setCode('A11740158','SNOMED');

findOrCreateMDCI($em,$fam,$form);

$soc = findOrCreateSection($em,'SOC','Social History');
findOrCreateMDCI($em,$soc,$form);
$soc->setCode("29762-2",'LOINC');

$socTob = findOrCreateSubsection($em,'TOB','Tobacco Use',$soc);
$socTob->setCode('A3210982','SNOMED');

$alcTob = findOrCreateSubsection($em,'ALC','Alcohol Intake',$soc);
$alcTob->setCode('A3245941','SNOMED');

$ros = findOrCreateSection($em,'ROS','Review of Systems');
$ros->setCode('10187-3','LOINC');
findOrCreateMDCI($em,$ros,$form);


$rosCON = findOrCreateSubsection($em,'ROS:CON','Review of Systems:Constitutional',$ros);
$rosCON->setCode('CON','ROS');

$rosEYE = findOrCreateSubsection($em,'ROS:EYE','Review of Systems:Eyes',$ros);
$rosEYE->setCode('EYE','ROS');

$rosENT = findOrCreateSubsection($em,'ROS:ENT','Review of Systems:Ears & Nose & Mouth & Throat',$ros);
$rosENT->setCode('ENT','ROS');


$rosCV = findOrCreateSubsection($em,'ROS:CV','Review of Systems:Cardiovascular',$ros);
$rosCV->setCode('CV','ROS');


$rosRES = findOrCreateSubsection($em,'ROS:RES','Review of Systems:Respiratory',$ros);
$rosRES->setCode('RES','ROS');


$rosGI = findOrCreateSubsection($em,'ROS:GI','Review of Systems:Gastrointestinal',$ros);
$rosGI->setCode('GI','ROS');


$rosGU = findOrCreateSubsection($em,'ROS:GU','Review of Systems:Genitourinary tract',$ros);
$rosGU->setCode('GU','ROS');

$rosMS = findOrCreateSubsection($em,'ROS:MS','Review of Systems:Musculoskeletal',$ros);
$rosMS->setCode('MS','ROS');


$rosINT = findOrCreateSubsection($em,'ROS:INT','Review of Systems:Integumentary',$ros);
$rosINT->setCode('INT','ROS');

$rosNEURO = findOrCreateSubsection($em,'ROS:NEURO','Review of Systems:Neurological',$ros);
$rosNEURO->setCode('NEURO','ROS');


$rosPSYCH = findOrCreateSubsection($em,'ROS:PSYCH','Review of Systems:Psychiatric',$ros);
$rosPSYCH->setCode('PSYCH','ROS');


$rosENDO = findOrCreateSubsection($em,'ROS:ENDO','Review of Systems:Endocrine',$ros);
$rosENDO->setCode('ENDO','ROS');


$rosHEM = findOrCreateSubsection($em,'ROS:HEM','Review of Systems:Hematologic',$ros);
$rosHEM->setCode('HEM','ROS');


$rosIMM = findOrCreateSubsection($em,'ROS:IMM','Review of Systems:Immunologic',$ros);
$rosIMM->setCode('IMM','ROS');


$pe = findOrCreateSection($em,'PE','Physical Exam');
$pe->setCode("22029-3","LOINC");
findOrCreateMDCI($em,$pe,$form);

$peVIT= findOrCreateSubsection($em,'PE:VIT','Physical Exam:Vital Signs',$pe,'34565-2','LOINC');

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
$peMS = findOrCreateSubsection($em,'PE:MS','Physical Exam:Musculoskeletal system',$pe,'11410-8','LOINC');

//Physical findings of Extremities
$peEXT = findOrCreateSubsection($em,'PE:EXT','Physical Exam:Extremities',$pe,'10196-4','LOINC');

//Physical findings of Nervous system
$peNER = findOrCreateSubsection($em,'PE:NER','Physical Exam:Nervous system',$pe,'10202-0','LOINC');

// Physical findings of Skin 10206-1
$peSKIN = findOrCreateSubsection($em,'PE:SKIN','Physical Exam:Skin',$pe,'10206-1','LOINC');

// Physical findings of Lymph node 32450-9
$peLYMPH = findOrCreateSubsection($em,'PE:LYMPH','Physical Exam:Lymphatics',$pe,'32450-9','LOINC');

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

// 29274-8 vital signs
//44100-6	Medical problem

$prob = findOrCreateSection($em,'PROB','Problem List');
findOrCreateMDCI($em,$prob,$ap);

$fu = findOrCreateSection($em,'FOLL','Follow Up');
findOrCreateMDCI($em,$fu,$ap);
$funar = findOrCreateNarrativeMetadata($em,'FUNAR','Follow Up Narrative');
findOrCreateMDCI($em,$funar,$fu);

$billing = findOrCreateSection($em,'BILL','Billing');
findOrCreateMDCI($em,$billing,$form);

$em->flush();
?>
