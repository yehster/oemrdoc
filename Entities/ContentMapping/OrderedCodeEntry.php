<?php
namespace library\doctrine\Entities\ContentMapping;

trait OrderedCodeEntry 
{
    public function  __construct($parent,$code,$code_type,$display_text,$seq)
    {
        $this->uuid=uuid_create();
        $this->content_group=$parent;
        $this->code=$code;
        $this->code_type=$code_type;
        $this->display_text=$display_text;
        $this->seq=$seq;
    }

    function getCode()
    {
        return $this->code;
    }

    function getCode_type()
    {
        return $this->code_type;
    }
    public function getContent_group()
    {
        return $this->content_group;
    }

    public function getDisplay_text()
    {
        return $this->display_text;
    }

    public function getUUID()
    {
        return $this->uuid;
    }    

    public function getSeq()
    {
        return $this->seq;
    }
    
    public function setSeq($val)
    {
        $this->seq=$val;
    }
 
    
    public function jsonSerialize()
    {
        $json_array=array();
        $json_array['uuid']=$this->getUUID();
        $json_array['code']=$this->getCode();
        $json_array['code_type']=$this->getCode_type();
        $json_array['display_text']=$this->getDisplay_text();
        return $json_array;
    }    
}
?>
