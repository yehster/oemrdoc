<?php

function classToType($class)
{
    switch($class)
    {
        case "normal":
        case "abnormal":
            return "Observation";
        case "multiple":
        case "exclusive":
            return "Nominative";
        case "quantitative":
            return "QuantitativeEntry";
        case "ShortNarrative":
            return "ShortNarrative";
    }
}

function findOrCreateVocab($em,$code,$parent,$code_type,$classification,$seq)
{

    $parItem=$parent->getItem();
    $type=classToType($classification);
    $objType="library\doctrine\Entities\\".$type;
    $qb = $em->createQueryBuilder()
        ->select("obs")
        ->from($objType,"obs")
        ->join("obs.item","i")
        ->where("obs.vocabID=:voc")
        ->andwhere("i.parent=:par")    ;
    $qb->setParameter("voc",$code);
    $qb->setParameter("par",$parItem);

    $qryRes=$qb->getQuery()->getResult();
    if(count($qryRes)===0)
    {
        $res = new $objType(null,$GLOBALS['pat'],$GLOBALS['user']);
        $newItem=$parItem->addEntry($res);
        if($seq!=-1)
        {
            $newItem->setSeq($seq);
        }
        $res->setvocabID($code);
    }
    else
    {
        $res = $qryRes[0];
    }
    return $res;

}
?>
