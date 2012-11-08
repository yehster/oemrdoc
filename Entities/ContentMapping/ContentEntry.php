<?php
namespace library\doctrine\Entities\ContentMapping;


/**
  * @Entity
  * @Table(name="dct_content_entries")
  */
class ContentEntry implements \JsonSerializable
{
    
    use OrderedCodeEntry;
    
    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;  
   
    /**
     * @ManyToOne(targetEntity="ContentGroup", inversedBy="content_entries", cascade={"persist"})
     * @JoinColumn(name="content_group", referencedColumnName="uuid")
     */
    protected $content_group;
    
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
    
    /**
     * @Column(type="integer") 
     */    
    protected $seq;    
    
   /**
    * @Column(type="string") 
    */
    protected $classification;

    public function setClassification($val)
    {
        $this->classification=$val;
    }
    
    public function jsonSerialize()
    {
        $retVal=OrderedCodeEntry::jsonSerialize();
        $retVal['classification']=$this->classification;
        return $retVal;
    }
    
            
}

?>
