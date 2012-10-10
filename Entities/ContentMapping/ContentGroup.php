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
        $this->ContentEntries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ClinicalContexts = new \Doctrine\Common\Collections\ArrayCollection();
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
      * @OneToMany(targetEntity="ContentEntry", mappedBy="ContentGroup", cascade={"persist","remove"})
      * @OrderBy({"seq" = "ASC"})
      */
    protected $ContentEntries;


    /**
      * @OneToMany(targetEntity="ClinicalContext", mappedBy="ContentGroup", cascade={"persist","remove"})
      * @OrderBy({"seq" = "ASC"})
      */
    protected $ClinicalContexts;
    
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
