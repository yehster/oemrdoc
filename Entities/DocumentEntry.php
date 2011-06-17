<?php
namespace library\doctrine\Entities;
include_once("OEMRProblem.php");

/** @Entity 
 *  @Table(name="dct_document_entries")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"sect" = "Section", "obs" = "Observation", "ord" = "Order",
   "nar" = "Narrative", "prob"="Problem", "med"="MedicationEntry",
   "nom" = "Nominative",
   "quant" = "QuantitativeEntry" })
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

        public function getMetadata()
        {
            return $this->metadata;
        }

	/** 
	 * @Column(type="datetime") 
	 */
	protected $created;


	/** 
	 * @Column(type="datetime") 
	 */
	protected $modified;

        public function getModified()
        {
            return $this->modified;
        }

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
            if($this->isLocked()){return;}
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

        public function getAuthor()
        {
            return $this->author;
        }

	/**
	 * @Column(type="string") 
 	 */
	protected $text;
	// Genericly the main text based information of this entry (a narative, nominative information, etc...) actual content varies with type

	// set the value of the text. this requires specifying the author
	public function setText($val,$auth)
	{
            if(!is_null($this->locked))
            {
		$this->text = $val;
		$this->author = $auth;
                if($this->OEMRListItem != null)
                {
                    $this->OEMRListItem->setTitle($val);
                }
            }
            $this->modified= new \DateTime;

	}

        /**
         * @Column(type="datetime")
         */
        protected $locked;

        public function getLocked()
        {
            
            return $this->locked;
        }
        
        public function isLocked()
        {
            return !is_null($this->locked);
        }

        public function lock($time)
        {
            if(!is_null($time))
            {
                $this->locked = $time;
            }
        }

        public function getText()
        {
            return $this->text;
        }


	/**
	  * @OneToOne(targetEntity="OEMRListItem",cascade={"persist", "remove", "merge"})
	  * @JoinColumn(name="OEMRListItem", referencedColumnName="id")
	  */
	protected $OEMRListItem;

        public function updateProperty($prop,$value)
        {
          if($this->isLocked()){return false;}            
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

        /**
         * @OneToOne(targetEntity="DocumentItem", mappedBy="entry",cascade={"persist","remove"})
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

        public function similar($comp)
        {
            // compares this object to another document entry
            try
            {
                if((get_class($comp)!=get_class($this))
                or ($comp->getCode_type()!=$this->getCode_type())
                or ($comp->getCode()!=$this->getCode()))
                { return false; }
                return true;
            }

            catch(Exception $e)
            {
                return false;
            }
        }

        public function copy($auth)
        {
            $type = get_class($this);
            $copy = new $type($this->md,$this->pat,$auth);
            $copy->text=$this->text;
            $copy->setCode($this->getCode(),$this->getCode_type());
            $copy->metadata=$this->metadata;
            
            return $copy;
        }
 }

 ?>
