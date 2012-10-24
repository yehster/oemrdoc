<?php

function findContentGroups($code,$code_type)
{
    $qb=$GLOBALS['em']->createQueryBuilder()
            ->select('cg')
            ->from('library\doctrine\Entities\ContentMapping\ContentGroup','cg')
            ->where('cg.document_context_code=:code')
            ->andWhere('cg.document_context_code_type=:code_type');
    $qb->setParameter("code",$code);
    $qb->setParameter("code_type",$code_type);
    
    $qry=$qb->getQuery();
    $res=$qry->getResult();
    
    return $res;
}

function loadContentEntries($contentGroup,&$values)
{
    $values['content_entries']=[];
    $contentEntries=$contentGroup->getContent_entries();
    foreach($contentEntries as $entry)
    {
        $values['content_entries'][]=$entry;
    }
}
function loadContextEntries($contentGroup,&$values)
{
    $values['context_entries']=[];
    $contextEntries=$contentGroup->getContext_entries();
    foreach($contextEntries as $entry)
    {
        $values['context_entries'][]=$entry;
    }
}

?>
