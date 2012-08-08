<?php
namespace library\doctrine\Entities\ContentMapping;

/**
  * @Entity
  * @Table(name="dct_content_groups")
  */
class ContentGroup {

    
    public function __construct($cb)
    {
	$this->uuid=uuid_create();
        $this->ContentEntries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->created_by=$cb;
    }    
    

    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;

    
      /**
	* @OneToMany(targetEntity="ContentEntry", mappedBy="ContentGroup", cascade={"persist","remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
    protected $ContentEntries;
    
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
    protected $clinical_context_code;
    
   /**
    * @Column(type="string") 
    */
    protected $clinical_context_code_type;

   /**
    * @Column(type="string") 
    */
    protected $created_by;
    
   /**
    * @Column(type="string") 
    */
    protected $description;
    
}

?>
