<?php
include('/var/www/openemr/library/doctrine/init-em.php');

function tokenize($str)
{
    $tokens = array();
    $idx=0;
    $tok = strtok($str," ");
    while($tok!==false)
    {
        $tokens[$idx]=$tok;
        $idx++;
        $tok=strtok(" ");
    }
    return $tokens;
}

function addCodeResult($DOM,$table,$code,$className)
{
    $newRow=$DOM->createElement("TR");
    $newRow->setAttribute("class",$className);
    $newRow->setAttribute("ID",$code->getId());

    $table->appendChild($newRow);

    $codeTextTD = $DOM->createElement("TD",$code->getCodeText());
    $codeTextTD->setAttribute("class",$className);

    $newRow->appendChild($codeTextTD);

    $codeTD = $DOM->createElement("TD",$code->getCode());
    $codeTD->setAttribute("class","CODE");
    $newRow->appendChild($codeTD);
}
function addKeyword($DOM,$table,$keyword,$className)
{
    $newRow = $DOM->createElement("TR");
    $newRow->setAttribute("class",$className);
    $table->appendChild($newRow);

    $keywordTD = $DOM->createElement("TD",$keyword->getContent());
    $newRow->setAttribute("CLASS","KEYWORD");
    $newRow->appendChild($keywordTD);

}

function addKeywordContent($DOM,$table,$keyword,$className)
{
    $newRow = $DOM->createElement("TR");
    $newRow->setAttribute("class",$className);
    $table->appendChild($newRow);

    $keywordTD = $DOM->createElement("TD",$keyword);
    $newRow->setAttribute("CLASS","KEYWORD");
    $newRow->appendChild($keywordTD);

}


function addMedName($DOM,$table,$mn,$className)
{
    $newRow = $DOM->createElement("TR");
    $newRow->setAttribute("CLASS",$className);
    $table->appendChild($newRow);


    $nameCell = $DOM->createElement("TD",  htmlentities($mn->getSTR()));
    $nameCell->setAttribute("RXCUI",$mn->getRXCUI());
    $nameCell->setAttribute("RXAUI",$mn->getRXAUI());
    $nameCell->setAttribute("CLASS","medName");
    $nameCell->setAttribute("TTY",$mn->getTTY());

    $newRow->appendChild($nameCell);
}

function findKeywords($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',keyword.content)";
    $qb = $em->createQueryBuilder()
        ->select("keyword,".$orderClause." as qual")
        ->where("keyword.content like :startsWith")
        ->from("library\doctrine\Entities\Keyword","keyword")
        ->orderBy("qual","DESC");

    $qb->setParameter("startsWith",$searchString[0]."%");
    $qry=$qb->getQuery();
    return $qry->getResult();
}


function findCodesForKeyword($em,$kw)
{


   $qb = $em->createQueryBuilder()
        ->select("code")
        ->from("library\doctrine\Entities\Code","code")
        ->from("library\doctrine\Entities\KeywordCodeAssociation", "kwa")
        ->where("kwa.code = code AND kwa.keyword=:kw");
//    $qb->setParameter("kw",$arrKeywords[0]);

   $qb->setParameter("kw",$kw->getId());
    $qry=$qb->getQuery();
    return $qry->getResult();
}

function lookupMedNames($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',mn.str)";
    $qb = $em->createQueryBuilder()
        ->select("mn,".$orderClause." as qual")
        ->from("library\doctrine\Entities\MedName","mn")
        ->where("mn.str like :startsWith")
        ->orderBy("qual DESC, mn.str");

    $qb->setParameter("startsWith",$searchString[0]."%");

    $qry=$qb->getQuery();
    return $qry->getResult();
}

function lookupMedForms($em,$rel,$types,$rxcui,$rxaui=null)
{
    $qb = $em->createQueryBuilder()
        ->select("co")
        ->from("library\doctrine\Entities\RXNConcept","co")
        ->from("library\doctrine\Entities\RXNRelationship", "rel")
        ->where("(rel.RXCUI1=:cui OR rel.RXAUI1=:aui)")
        ->andWhere("rel.RXCUI2 = co.RXCUI")
        ->andWhere("rel.RELA = :rel")
        ->andWhere("co.TTY in(".$types.")")
        ->orderBy("co.TTY");

    $qb->setParameter("cui",$rxcui);
    $qb->setParameter("aui",$rxaui);
    $qb->setParameter("rel",$rel);

    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)==0)
    {
        // If there are no results, then we need to check for synonyms

    }
    return $res;
}

function findCodesForKwArr($em,$kwarr,$tok)
{
    // need to fix scoring order
    $kwinList=array();
    $numTok=count($kwarr);
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $kwinList[$tokIdx]="";
        $keywords=$kwarr[$tokIdx];
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=$minMatch + ($maxMatch-$minMatch)*0.05 ;
        for($kwIdx=0;$kwIdx<count($keywords) && ($keywords[$kwIdx]['qual']>=$tol) ;$kwIdx++)
        {
            $kwID=$keywords[$kwIdx][0]->getID();
            $kwinList[$tokIdx]=$kwinList[$tokIdx].",".$kwID;
        }
        $kwinList[$tokIdx]="(". substr($kwinList[$tokIdx],1).")";
    }
    $quals = "";
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $qualStr="MATCHQUALITY('".$tok[$tokIdx]."',kw".$tokIdx.".content) as qual".$tokIdx;
        $quals=$quals.",".$qualStr.",kw".$tokIdx.".content as content".$tokIdx;
    }
    $qb = $em->createQueryBuilder()
        ->select("cd".$quals);
     $qb->from("library\doctrine\Entities\Code", "cd");
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $qb->from("library\doctrine\Entities\KeywordCodeAssociation","kwc".$tokIdx);
        $qb->from("library\doctrine\Entities\Keyword","kw".$tokIdx);
        $qb->andWhere("kwc".$tokIdx.".keyword in ".$kwinList[$tokIdx]);
        $qb->andWhere("kwc".$tokIdx.".code = cd");
        $qb->andWhere("kwc".$tokIdx.".keyword = kw".$tokIdx);
        $qb->orderBy("kw".$tokIdx.".content" ,"DESC");
//        $qb->orderBy("qual".$tokIdx,"DESC");
// TODO: tweak sort ordering issues
     }

    $qry=$qb->getQuery();
    $res=$qry->getResult();
    return $res;
}


$ResultsDom = new DOMDocument("1.0","utf-8");
$closeDIV = $ResultsDom->createElement("BUTTON","close");
$closeDIV->setAttribute("class","ClosePopup");
$closeDIV->setAttribute("TYPE","button");
$ResultsDom->appendChild($closeDIV);
if(isset($_REQUEST['searchString']))
{
    $searchString = $_REQUEST['searchString'];
}
if(isset($_REQUEST['rxcui']))
{
    $rxcui = $_REQUEST['rxcui'];
}
if(isset($_REQUEST['rxaui']))
{
    $rxaui = $_REQUEST['rxaui'];
}


if(isset($_REQUEST['context']))
{
    $context = $_REQUEST['context'];
}
if(isset($_REQUEST['className']))
{
    $className = $_REQUEST['className'];
}
if(isset($_REQUEST['maxRes']))
{
    $maxRes = $_REQUEST['maxRes'];
}


if($context=="code")
{
    $table = $ResultsDom->CreateElement("TABLE");
    $ResultsDom->appendChild($table);
    $toks = tokenize($searchString);
    if(count($toks)==1)
    {
        $keywords = findKeywords($em,$toks[0]);
        $maxMatch=$keywords[0]['qual'];
        $minMatch=$keywords[count($keywords)-1]['qual'];
        $tol=($maxMatch-$minMatch) / 2;
        if(strlen($toks[0])==1)
        {
            $maxRes=20;
        }
        else {
            $maxRes=9999;
        }
        for($idx=0;$idx<count($keywords) and ((($keywords[$idx]['qual']) - $maxMatch + $tol) >= 0) and $idx<$maxRes;$idx++)
        {
            $curKW = $keywords[$idx][0];
            addKeyword($ResultsDom,$table,$curKW,"");
            $codes = findCodesForKeyword($em,$curKW);
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                addCodeResult($ResultsDom,$table,$codes[$cidx],$className);
            }
        }
        echo $ResultsDom->saveXML();
    }
    else
    {
        if(count($toks)>1)
        {
            $kwarr=array();
            for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
            {
                $kwarr[$tokIdx] = findKeywords($em,$toks[$tokIdx]);
            }
            $codes=findCodesForKwArr($em,$kwarr,$toks);
            $lastKW = array();
            for($cidx=0;$cidx<count($codes);$cidx++)
            {
                for($tokIdx=0;$tokIdx<count($toks);$tokIdx++)
                {
                    if($lastKW[$tokIdx]!==$codes[$cidx]['content'.$tokIdx])
                    {
                        $lastKW[$tokIdx]=$codes[$cidx]['content'.$tokIdx];
                        addKeywordContent($ResultsDom,$table,$lastKW[$tokIdx],"");
                    }
                }
                $curCode=$codes[$cidx][0];
                addCodeResult($ResultsDom,$table,$curCode,$className);
            }

        }
        echo $ResultsDom->saveXML();
    }
}
if($context=="med")
{
    $medNames = lookupMedNames($em,$searchString);
    $drugsTable = $ResultsDom->createElement("TABLE");
    $ResultsDom->appendChild($drugsTable);
    for($idx=0;$idx<$maxRes and $idx<count($medNames);$idx++)
    {
        $curMed = $medNames[$idx][0];
        addMedName($ResultsDom,$drugsTable,$curMed,$className);
    }
    echo $ResultsDom->saveXML();
}

if($context=="medSemantic")
{
    $medForms= lookupMedForms($em,"has_ingredient","'SCDF','SBDF'",$rxcui,$rxaui);
    for($idx=0;$idx<count($medForms);$idx++)
    {
        $drugsTable = $ResultsDom->createElement("TABLE");
        $ResultsDom->appendChild($drugsTable);
        $curMed = $medForms[$idx];
        addMedName($ResultsDom,$drugsTable,$curMed,$className);
        $medSem = lookupMedForms($em,"isa","'SCD','SBD'",$curMed->getRXCUI(),$curMed->getRXAUI());
        for($formIdx=0;$formIdx<count($medSem);$formIdx++)
        {
            $curSem = $medSem[$formIdx];
            addMedName($ResultsDom,$drugsTable,$curSem,"SpecifiedDrug");
        }
        echo $ResultsDom->saveXML($drugsTable);
    }
}

?>