<?php

namespace library\doctrine\Entities;

/** @Entity 
*	@Table(name="dct_documents")
*/
class Document
 {
 
	/** A document represents  any sort of data tracked by the system.  It could be a physician's note for an office visit, 
	or it could be a lab report or other information received from an external source.  Document Entries represent specific pieces of information
	while document items represent linkages/organizational structure of information.  Thus a single document entry could be first entered into the system in an external lab report, then an interesting value could be linked into the physician's note. */

    public function __construct($pat,$aut,$md)
    {
        // this function initializes a new document and subitems based on the structure of the metadata passed to it.
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->patient = $pat;
        $this->author = $aut;
        $this->metadata = $md;

        // recursively construct the sub-metadata items
        foreach($md->getItems() as $value)
        {
            $docItem = new DocumentItem($this,$value->getMetadata(),$pat,$aut);
            $this->addItem($docItem);
        }
    }

        const classtype = "Document";

        public function getType()
        {
            return self::classtype;
        }
    	/**
	* @ManyToOne(targetEntity="DocumentMetadata")
	* @JoinColumn(name="metadata_id", referencedColumnName="uuid")
	*/
        private $metadata;

        public function getMetadata()
        {
            return $this->metadata;
        }

	/**
	 * @Id 
	 * @Column(type="string") 
	 */
	private $uuid;

        public function getUUID()
        {
            return $this->uuid;
        }

	/** 
	 * @Column(type="datetime") 
	 */
	private $created;


	/** 
	 * @Column(type="datetime") 
	 */
	protected $modified;
        public function getModified()
        {
            return $this->modified;
        }
	
	/**
	* @OneToMany(targetEntity="DocumentItem", mappedBy="document", cascade={"persist", "remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
	private $items;

        public function getItems()
        {
            return $this->items;
        }

	public function addItem($obj)
	{
		$this->items->add($obj);
		$obj->setDocument($this);
		$obj->setSeq($this->items->count());
	}

	/**
	* @ManyToOne(targetEntity="Patient")
	* @JoinColumn(name="patient_id", referencedColumnName="pid")
	*/
	private $patient;

	/**
         * @Column(type="string")
	 */
	private $author;
	// Who wrote this entry.  If an entry is modified, the author will be updated, this could be an internal user or an external source.
	// may need to expand this entry to additional fields to accommodate HL7 source info. if the author isn't specified, assume the parent entry's
	// author is the author.
        
        public function getAuthor()
        {
            return $this->author;
        }


 }
 
 ?>
