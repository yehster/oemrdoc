<?php
namespace library\doctrine\Entities;
include_once("OEMRProblem.php");

/** @Entity 
 *  @Table(name="dct_document_entries")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"sect" = "Section", "obs" = "Observation", "ord" = "Order", "nar" = "Narrative", "prob"="Problem", "med"="MedicationEntry"})
 */
 class DocumentEntry
 {
    public function __construct($md,$pat,$auth)
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->metadata = $md;
        $this->patient = $pat;
        $this->author = $auth;
        $this->locked = false;
    }

    const classtype = "DocumentEntry";
    public function getType()
    {
        return self::classtype;
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
	* @ManyToOne(targetEntity="DocumentMetadata")
	* @JoinColumn(name="metadata_id", referencedColumnName="uuid")
	*/
        protected $metadata;
 
	/** 
	 * @Column(type="datetime") 
	 */
	protected $created;


	/** 
	 * @Column(type="datetime") 
	 */
	protected $modified;

	/**
	* @ManyToOne(targetEntity="Patient")
	* @JoinColumn(name="patient_id", referencedColumnName="pid")
	*/
	protected $patient;

        public function getPatient()
        {
            return $this->patient;
        }
	/**
	 * @Column(type="string") 
 	 */
	protected $code;
	// the value of the code entry

        public function getCode()
        {
            return $this->code;
        }
	/**
	 * @Column(type="string") 
 	 */
	protected $code_type;
	// is this an icd-9 code, or a LOINC code etc.

        public function getCode_type()
        {
            return $this->code_type;
        }

	public function setCode($val,$for)
	{
		$this->code = $val;
		$this->code_type = $for;
	}
	

	/**
	 * @Column(type="string") 
 	 */
	protected $author;
	// Who wrote this entry.  If an entry is modified, the author will be updated, this could be an internal user or an external source.
	// may need to expand this entry to additional fields to accommodate HL7 source info. if the author isn't specified, assume the parent entry's
	// author is the author.

	/**
	 * @Column(type="string") 
 	 */
	protected $text;
	// Genericly the main text based information of this entry (a narative, nominative information, etc...) actual content varies with type

	// set the value of the text. this requires specifying the author
	public function setText($val,$auth)
	{
            if(!$this->locked)
            {
		$this->text = $val;
		$this->author = $auth;
                if($this->OEMRListItem != null)
                {
                    $this->OEMRListItem->setTitle($val);
                }
            }

	}

        /**
         * @Column(type="boolean")
         */
        protected $locked;

        public function getLocked()
        {
            
            return $this->locked;
        }

        public function getText()
        {
            return $this->text;
        }

	// The history of an entry is maintained as a doubly linked list to older (prev) and newer (next)documentEntry items
	// Need to understand difference between "history" e.g. updates to the problem/item over visits and revisions (changing an item in an existing document.)
        

	/**
	  * @OneToOne(targetEntity="DocumentEntry")
	  * @JoinColumn(name="prevVersion", referencedColumnName="uuid")
	  */
	protected $prevVersion;
	
	/**
	  * @OneToOne(targetEntity="DocumentEntry")
	  * @JoinColumn(name="nextVersion", referencedColumnName="uuid")
	  */
	protected $nextVersion;
	// The history of document entry is represented as a linked list to prior entries.

        // This function creates tag info (class and id) for html elements to use
        public function getTagInfo()
        {
            $info = " id='".$this->getUUID()."' "." class='".self::classtype."'";
            return $info;
        }

        public function getTag()
        {
            return "DIV";
        }


	/**
	  * @OneToOne(targetEntity="OEMRListItem",cascade={"persist", "remove", "merge"})
	  * @JoinColumn(name="OEMRListItem", referencedColumnName="id")
	  */
	protected $OEMRListItem;

        public function updateProperty($prop,$value)
        {
          if($this->locked!=true)
          {
            if(($prop!="uuid") &&
                 ($prop!="created") &&
                 ($prop!="modified") &&
                 ($prop!="author") &&
                 ($prop!="metadata") &&
                 ($prop!="patient") &&
                 ($prop!="locked")
                  )
            {
              if($this->$prop!=$value)
              {
                $this->$prop=$value;
                $this->modified = new \DateTime();
              }
              return true;
            }
          }
          return false;
        }

        /**
         * @OneToOne(targetEntity="DocumentItem", mappedBy="entry", cascade={"persist","remove"})
        */
        protected $item;

        public function getItem()
        {
            return $this->item;
        }

        public function getParentEntry()
        {
            return $this->getItem()->getParent()->getEntry();
        }
 }

 ?>
