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
    $values['display_texts']=[];
    $values['codes']=[];
    $values['code_types']=[];
    $values['uuids']=[];
    $contentEntries=$contentGroup->getContent_entries();
    foreach($contentEntries as $entry)
    {
        $values['display_texts'][]=$entry->getDisplay_text();
        $values['codes'][]=$entry->getCode();
        $values['code_types'][]=$entry->getCode_type();
        $values['uuids'][]=$entry->getUUID();
    }        
}
?>
