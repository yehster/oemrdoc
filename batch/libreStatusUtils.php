<?php

function findLibreFiles($em,$filenames)
{
   $inClause="(";
   for($idx=0;$idx<count($filenames);$idx++)
   {
       $fn=$filenames[$idx];
       $fn=substr($fn,0,strlen($fn)-4);
       $inClause.= "'" . $fn ."'" .",";
   }
   
   $inClause=substr($inClause,0,strlen($inClause)-1).")";
   error_log($inClause);
   $qb = $em->createQueryBuilder()
        ->select("file");
        $qb->from("library\doctrine\Entities\libre\libreFile","file")
        ->where("file.filename in ".$inClause);
    $qry=$qb->getQuery();
    return $qry->getResult();
}
?>
