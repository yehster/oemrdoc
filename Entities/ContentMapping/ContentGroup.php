<?php
namespace library\doctrine\Entities\ContentMapping;

/**
  * @Entity
  * @Table(name="dct_content_groups")
  */
class ContentGroup {

    
    public function __construct($cb,$dc,$dct,$desc)
    {
	$this->uuid=uuid_create();
        $this->content_entries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->context_entries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created_by=$cb;
        $this->document_context_code=$dc;
        $this->document_context_code_type=$dct;
        $this->description=$desc;
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
      * @OneToMany(targetEntity="ContentEntry", mappedBy="content_group", cascade={"persist","remove"})
      * @OrderBy({"seq" = "ASC"})
      */
    protected $content_entries;

    public function getContent_entries()
    {
        return $this->content_entries;
    }
    
    public function createContent_entry($code,$code_type,$code_display_text)
    {
        $num_entries=count($this->content_entries);
        if($num_entries==0)
        {
            $seq=0;
        }
        else
        {
            $seq=$this->content_entries->last()->getSeq()+1;
        }
        $new_content_entry = new ContentEntry($this,$code,$code_type,$code_display_text,$seq);
        $this->content_entries->add($new_content_entry);
        return $new_content_entry;
    }
    /**
      * @OneToMany(targetEntity="ContextEntry", mappedBy="content_group", cascade={"persist","remove"})
      * @OrderBy({"seq" = "ASC"})
      */
    protected $context_entries;

    public function createContext_entry($code,$code_type,$code_display_text)
    {
        $num_entries=count($this->context_entries);
        if($num_entries==0)
        {
            $seq=0;
        }
        else
        {
            $seq=$this->context_entries->last()->getSeq()+1;
        }
        $new_context_entry = new ContextEntry($this,$code,$code_type,$code_display_text,$seq);
        $this->context_entries->add($new_context_entry);
        return $new_context_entry;
    }
    
    public function getContext_entries()
    {
        return $this->context_entries;
    }    
   /**
    * @Column(type="string") 
    */
    protected $document_context_code;

   /**
    * @Column(type="string") 
    */
    protected $document_context_code_type;

   /**
    * @Column(type="string") 
    */
    protected $created_by;
    
   /**
    * @Column(type="string") 
    */
    protected $description;
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($val)
    {
        $this->description=$val;
    }
    
}

?>
