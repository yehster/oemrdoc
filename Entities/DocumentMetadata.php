<?php

namespace library\doctrine\Entities;

/** @Entity 
 *  @Table(name="dct_document_metadata")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"doc"= "DocumentType",
 *                     "sect" = "SectionHeading", 
 *                     "narmd"="NarrativeMetadata",
 *                     "trans"="TranscriptionInfoMetadata"
 * })
 */
 class DocumentMetadata
{
    public function __construct($sd,$ld)
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
	$this->shortDesc = $sd;
	$this->longDesc = $ld;
    }
 
	/**
	 * @Id 
	 * @Column(type="string") 
	 */
	protected $uuid;

        public function getuuid()
        {
            return $this->uuid;
        }
        

	/** 
	 * @Column(type="datetime") 
	 */
	protected $created;


	/** 
	 * @Column(type="datetime") 
	 */
	protected $modified;

	/**
	 * @Column(type="string") 
	 */
	protected $shortDesc;

        public function getshortDesc()
        {
            return $this->shortDesc;
        }

        public function getText()
        {
            return $this->longDesc;
        }
        
	/**
	 * @Column(type="string") 
	 */
	protected $longDesc;

        public function getlongDesc()
        {
            return $this->longDesc;
        }

      /**
	* @OneToMany(targetEntity="DocumentMetadataCollectionItem", mappedBy="parent", cascade={"persist","remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
	protected $items;
	// These are the child items of a parent metadata descriptor

        /**
         * @OneToOne(targetEntity="DocumentMetadataCollectionItem", mappedBy="metadata",cascade={"persist","remove"})* 
         */
        protected $ci;
        
	public function addItem($obj)
	{
		$this->items->add($obj);
		$obj->setParent($this);
		$obj->setSeq($this->items->count());
	}

        public function getItems()
        {
            return $this->items;
        }

        public function addMetadata($md)
        {
            $mdci=new DocumentMetadataCollectionItem($md);
            $this->addItem($mdci);
        }
        public function makeEntry(Patient $pat,$auth)
        {
            return new DocumentEntry($this,$pat,$auth);
        }

}
?>
