<?php
function lookupKeywords($em,$searchString)
{
    $orderClause = "MATCHQUALITY('".$searchString."',keyword.text)";
    $qb = $em->createQueryBuilder()
        ->select("keyword,".$orderClause." as qual")
        ->where("keyword.text like :startsWith")
        ->from("library\doctrine\Entities\ICD9\ICD9Keyword","keyword")           
        ->orderBy("qual","DESC");
    $qb->addOrderBy("keyword.frequency","DESC");
    for($charIdx=1;$charIdx<strlen($searchString) && $charIdx<3; $charIdx++)
    {
        $qb->andWhere("keyword.text like :char".$charIdx);
        $qb->setParameter("char".$charIdx,"%".$searchString[$charIdx]."%");
    }

    $qb->setParameter("startsWith",$searchString[0]."%");

    $qry=$qb->getQuery();
    $qry->setFirstResult(0);
    $qry->setMaxResults(100);

    return $qry->getResult();
    
}

function findKeywords($em,$toks)
{
    $retval=array();
    for($idx=0;$idx<count($toks);$idx++)
    {
        $retval[$idx]=lookupKeywords($em,$toks[$idx]);
    }
    return $retval;
}

function findCodes($em,$kwArr,$toks)
{
    $kwInList=array();
    $numTok=count($kwArr);
    $qb = $em->createQueryBuilder();

    $quals="";
    $orderByString="";
    
    $qb->select("cd");
    $qb->addOrderBy("cd.frequency","DESC");
    for($tokIdx=0;$tokIdx<$numTok;$tokIdx++)
    {
        $keywords=$kwArr[$tokIdx];
        $kwInList[$tokIdx]="";
        for($kwIdx=0;$kwIdx<count($keywords);$kwIdx++)
        {
            $kwInList[$tokIdx].=",".$keywords[$kwIdx][0]->getID();
        }
        $kwInList[$tokIdx]="(". substr($kwInList[$tokIdx],1).")";

        $qb->addSelect("MATCHQUALITY('".$toks[$tokIdx]."',kw".$tokIdx.".text) as qual".$tokIdx);
        $qb->addSelect("kw".$tokIdx.".text as content".$tokIdx);

        
        $qb->from("library\doctrine\Entities\ICD9\ICD9KeywordMapping","kwm".$tokIdx);
        $qb->from("library\doctrine\Entities\ICD9\ICD9Keyword","kw".$tokIdx);
        $qb->andWhere("kwm".$tokIdx.".keyword in ".$kwInList[$tokIdx]);
        $qb->andWhere("kwm".$tokIdx.".code = cd");
        $qb->andWhere("kwm".$tokIdx.".keyword = kw".$tokIdx);

        $qb->addOrderBy('qual'.$tokIdx,"DESC");
        $qb->addOrderBy("kw".$tokIdx.".frequency","DESC");
        $qb->addOrderBy('kwm'.$tokIdx.".priority","ASC");
        $qb->addOrderBy('kw'.$tokIdx.".text","ASC");
        
    }

    $qb->from("library\doctrine\Entities\ICD9\ICD9Code", "cd");
    
    $qb->addOrderBy("cd.code","ASC");

    $qry=$qb->getQuery();
  
    $qry->setFirstResult(0);
    $qry->setMaxResults(100);


    error_log($qb->getDql());
    error_log($qry->getSql());
    $res=$qry->getResult();
    error_log("query succeeded");
    return $res;
    
    
}

function generate_table($codes)
{
    $DOM=new DOMDocument("1.0","utf-8");
    $table = $DOM->createElement("table");
    $tbody= $DOM->createElement("tbody");
    $table->appendChild($tbody);
    $keywords=array();
    foreach($codes as $result)
    {
        $code=$result[0];
        $tr=$DOM->createElement("tr");
        if($code->getFrequency()>0)
        {
            $tr->setAttribute("class","priority");
        }
        $idx=0;
        $update=false;
        while(isset($result['content'.$idx]))
        {
            if(isset($keywords[$idx]))
            {
                if($keywords[$idx]!=$result['content'.$idx])
                {
                    $keywords[$idx]=$result['content'.$idx];
                    $update=true;                
                }
            }
            else
            {
                $update=true;
                $keywords[$idx]=$result['content'.$idx];
            }
            $idx++;
        }
        $tdCodeDesc=$DOM->createElement("td",htmlentities($code->getShort_desc()));
        $tdCodeDesc->setAttribute("class","codeDesc");
        $defs=$code->getDefinitions();
        if(count($defs)>0)
        {
            $tdCodeDesc->setAttribute("defs",count($defs));
        }
        $tdCodeID=$DOM->createElement("td",$code->getCode());
        $tdCodeID->setAttribute("class","codeNum");
        $tr->setAttribute("type",$code->type);
        
        $tr->appendChild($tdCodeDesc);
        $tr->appendChild($tdCodeID);
        $keywordString=implode(",",$keywords);
        if($update)
        {
            $trKeywords=$DOM->createElement("tr");
            $tdKeywords=$DOM->createElement("td",$keywordString);
            $trKeywords->appendChild($tdKeywords);
            $trKeywords->setAttribute("class","keywords");
            $tbody->appendChild($trKeywords);
        }
        $tr->setAttribute("keywords",$keywordString);
        $tbody->appendChild($tr);
    }
    return $DOM->saveXML($table);
}

?>
