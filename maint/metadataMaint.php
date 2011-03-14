<?php

function findOrCreateSubsection($em,$sd,$ld,$par,$code="",$codeType="")
{
    $retval = findOrCreateSection($em,$sd,$ld);
    if($code!="")
    {
        $retval->setCode($code,$codeType);
    }
    findOrCreateMDCI($em,$retval,$par);
    return $retval;

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

?>
