<?php
namespace library\doctrine\Entities\ContentMapping;

/**
  * @Entity
  * @Table(name="dct_content_mapping")
  */
class ContentMapping {

    protected $uuid;
    protected $document_context_code;
    protected $document_context_code_type;
    
    protected $clinical_context_code;
    protected $clinical_context_code_type;
    
    protected $concept_code;
    protected $concept_code_type;
    
    protected $concept_type;
    
    protected $seq;
    
    protected $group_id;
    
}

?>
