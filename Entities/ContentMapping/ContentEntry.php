<?php
namespace library\doctrine\Entities\ContentMapping;


/**
  * @Entity
  * @Table(name="dct_content_entries")
  */
class ContentEntry {
    public function  __construct($parent,$code,$code_type,$display_text,$seq)
    {
        $this->uuid=uuid_create();
        $this->content_group=$parent;
        $this->code=$code;
        $this->code_type=$code_type;
        $this->display_text=$display_text;
        $this->seq=$seq;
    }
    
    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;
    
    public function getUUID()
    {
        return $this->uuid;
    }
    /**
     * @ManyToOne(targetEntity="ContentGroup", inversedBy="ContentEntries", cascade={"persist"})
     * @JoinColumn(name="content_group", referencedColumnName="uuid")
     */
    protected $content_group;
    
    public function getContent_group()
    {
        return $this->content_group;
    }
   /**
    * @Column(type="string") 
    */
    protected $code;

    public function getCode()
    {
        return $this->code;
    }
    
   /**
    * @Column(type="string") 
    */
    protected $code_type;
    
    public function getCode_type()
    {
        return $this->code_type;
    }
    
   /**
    * @Column(type="string") 
    */
    protected $display_text;
    
    public function getDisplay_text()
    {
        return $this->display_text;
    }
    
    /**
     * @Column(type="integer") 
     */    
    protected $seq;
    
    public function getSeq()
    {
        return $this->seq;
    }
    
    public function setSeq($val)
    {
        $this->seq=$val;
    }
}

?>
