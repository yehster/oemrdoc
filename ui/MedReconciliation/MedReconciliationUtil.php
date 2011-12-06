<?php

function findOrCreateMedReconciliation($em,$pat,$user)
{
        $qb=$em->createQueryBuilder()
        ->select("d")
        ->from("library\doctrine\Entities\Document","d")
        ->join("d.metadata","md")
        ->where("d.patient=:pat")
        ->andWhere("d.author=:aut")
        ->andWhere("((d.locked is null) or (d.locked=false))")
        ->andWhere("((d.removed is null) or (d.removed=false))")
        ->andWhere("md.shortDesc='MR'")
        ->orderBy("d.modified","desc");
        // Specify parameters for the parent entry
        $qb->setParameter("pat",$pat);
        $qb->setParameter("aut",$user->getUsername());

        $res=$qb->getQuery()->getResult();
        if(count($res)>0)
        {
            return $res[0];
        }
        else
        {
            $md=$em->getRepository('library\doctrine\Entities\DocumentMetadata')->findOneByshortDesc("MR");
            $doc = new library\doctrine\Entities\Document($pat,$user->getUsername(),$md);
            $em->persist($doc);
            $em->flush();
            return $doc;
        }
}
?>
