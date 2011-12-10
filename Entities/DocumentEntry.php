<?php
namespace library\doctrine\Entities;
include_once("OEMRProblem.php");
include_once("EntryStatusCodes.php");
/** @Entity 
 *  @Table(name="dct_document_entries")
 *  @HasLifecycleCallbacks
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"sect" = "Section", "obs" = "Observation", "ord" = "Order",
   "nar" = "Narrative", "prob"="Problem", "med"="MedicationEntry",
   "nom" = "Nominative",
   "quant" = "QuantitativeEntry",
   "shn" = "ShortNarrative",
   "img" = "ImageEntry",
   "medsig" = "MedicationSIG",
   "bill" = "BillingEntry",
   "link" = "DocumentLink"})
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
        $this->locked = null;
        $this->statusHistory = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @OneToMany(targetEntity="EntryStatus", mappedBy="entry", cascade={"remove"})
         * @OrderBy({"modified"="DESC"})
	 */
        protected $statusHistory;

        public function getStatusHistory()
        {
            return $this->statusHistory;
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

        public function getCreated()
        {
            return $this->created;
        }

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
            if(!$this->isLocked())
            {
		$this->text = $val;
		$this->author = $auth;
            }

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

        protected $locking=false;
        public function lock($time)
        {
            if(!is_null($time))
            {
                $this->locked = $time;
                $this->locking=true;
            }
        }

        public function getText()
        {
            return $this->text;
        }


        
	/**
	  * @OneToOne(targetEntity="OEMRListItem")
	  * @JoinColumn(name="OEMRListItem", referencedColumnName="id")
	  */
	protected $OEMRListItem;

        public function getOEMRListItem()
        {
            return $this->OEMRListItem;
        }
        
        public function setOEMRListItem($val)
        {
            $this->OEMRListItem=$val;
        }
        /**
         * @OneToOne(targetEntity="DocumentItem", mappedBy="entry",cascade={"persist","remove"})
        */
        protected $item;

        public function getItem()
        {
            return $this->item;
        }
        
        public function setItem($val)
        {
            $this->item=$val;
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
                if(isset($this->copiedFrom))
                {
                    if($this->copiedFrom===$comp)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return true;
                }
            }

            catch(Exception $e)
            {
                return false;
            }
        }

        public function copy($auth)
        {
            $type = get_class($this);
            $copy = new $type($this->metadata,$this->patient,$auth);
            $copy->text=$this->text;
            $copy->setCode($this->getCode(),$this->getCode_type());
            $copy->metadata=$this->metadata;
            $copy->copiedFrom=$this;
            $copy->created=$this->created;
            return $copy;
        }

    
        /**
	* @ManyToOne(targetEntity="DocumentEntry",inversedBy="copiedTo")
	* @JoinColumn(name="copiedFrom_id", referencedColumnName="uuid")
	*/
        protected $copiedFrom;
        
        public function getCopiedFrom()
        {
            return $this->copiedFrom;
        }
        
        /**
         *
         * @OneToMany(targetEntity="DocumentEntry",mappedBy="copiedFrom")
         */
        protected $copiedTo;
    
    

        
        
        public function getStatus()
        {
            if(count($this->getStatusHistory())>0)
            {
                $sh=$this->getStatusHistory();
                return $sh[0];
            }
            else
            {
                if(isset($SESSION['authUser']))
                {
                    $auth=$SESSION['authUser'];
                }
                
                $status = new EntryStatus($this,$this->author,STATUS_ACTIVE);
                $this->getStatusHistory()->add($status);
                $GLOBALS['em']->persist($status);
                $GLOBALS['em']->flush();
                return $status;
            }
        }
        public function setStatus($val)
        {
                $status = new EntryStatus($this,$this->author,$val);
                $this->getStatusHistory()->add($status);
                if($val<0)
                {
                    if($this->OEMRListItem!=null)
                    {
                        $this->OEMRListItem->setEndDate(new \DateTime);
                    }
                }
                $GLOBALS['em']->persist($status);
                $GLOBALS['em']->flush();
        }
    /** @PreRemove */
    public function preventRemoveOfLocked()
    {
        if($this->isLocked())
        {
            throw new \Exception("Cannot Remove Locked Entry");
        }
    }

    
    /** @PreUpdate */
    public function preUpdateEventHandler()
    {
     
        // Prevent update of locked
        if($this->isLocked())
        {
            if(!$this->locking)
            {
                throw new \Exception("Cannot Update Locked Entry");
            }
            $this->locking=false;
        }
        
        // update modified
        $this->modified = new \DateTime;
    }

    
    
}

 ?>
