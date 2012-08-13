<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function findOrCreateGroup($sec_code,$sec_code_type,$context_code,$context_code_type)
{
    $user = $_SESSION['authUser'];
    $em=$GLOBALS['em'];
    $qb=$em->createQueryBuilder();
    $qb->select("grp")
       ->from("library\doctrine\Entities\ContentMapping\ContentGroup","grp")
       ->where("grp.document_context_code=:doc_code")
       ->andWhere("grp.document_context_code_type=:doc_code_type")
       ->andWhere("grp.clinical_context_code=:clinical_code")
       ->andWhere("grp.clinical_context_code_type=:clinical_code_type");
    $qb->setParameter("doc_code",$sec_code);
    $qb->setParameter("doc_code_type",$sec_code_type);
    $qb->setParameter("clinical_code",$context_code);
    $qb->setParameter("clinical_code_type",$context_code_type);
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    if(count($res)==0)
    {
        $retVal=new library\doctrine\Entities\ContentMapping\ContentGroup($user,$sec_code,$sec_code_type,$context_code,$context_code_type);
        $em->persist($retVal);
        $em->flush();
        return ($retVal);
    }
    else
    {
        return $res[0];
    }
}
?>
