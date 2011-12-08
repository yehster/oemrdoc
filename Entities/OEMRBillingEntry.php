<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="billing")
 */
class OEMRBillingEntry{
//`billing`.`id`, `billing`.`date`, `billing`.`code_type`, `billing`.`code`, `billing`.`pid`, `billing`.`provider_id`, `billing`.`user`, `billing`.`groupname`, `billing`.`authorized`, `billing`.`encounter`, `billing`.`code_text`, `billing`.`billed`, `billing`.`activity`, `billing`.`payer_id`, `billing`.`bill_process`, `billing`.`bill_date`, `billing`.`process_date`, `billing`.`process_file`, `billing`.`modifier`, `billing`.`units`, `billing`.`fee`, `billing`.`justify`, `billing`.`target`, `billing`.`x12_partner_id`, `billing`.`ndc_info`

    function __construct($enc,$user)
    {
        $this->date=new \DateTime();
        $this->encounter=$enc;
        $this->billed=0;
        $this->activity=1;
        $this->user=$user;
    }
    
    /**
	 * @Id
	 * @Column(type="integer")
         * @GeneratedValue
	 */
	protected $id;

        public function getId()
        {
            return $this->id;
        }
        /**
         * @Column(type="datetime")
         */
        protected $date;        
        
        /**
	 * @Column(type="integer")
	 */
        protected $pid;

        public function setPID($val)
        {
            $this->pid=$val;
        }
        /**
	 * @Column(type="string") 
 	 */
	protected $code;
	// the value of the code entry

        public function setCode($val)
        {
            $this->code=$val;
        }
        
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

        public function setCode_type($val)
        {
            $this->code_type=$val;
        }

        public function getCodeTypeText()
        {
            switch ($this->code_type) 
            {
                    case "1":
                        return "CPT4";
                        break;
                    case "2":
                        return "ICD9";
                        break;
            }
        }
	/**
	 * @Column(type="string") 
 	 */
	protected $code_text;
        
        public function setCode_text($val)
        {
            $this->code_text=$val;
        }
        
        public function getCode_text()
        {
            return $this->code_text;
        }
        
        /**
         * @Column(type="float")
         */        
        protected $fee;

        function setFee($val)
        {
            $this->fee=$val;
        }
        
        function getFee()
        {
            return $this->fee;
        }
	/**
	 * @Column(type="string") 
 	 */
        protected $justify;
        
        public function setJustify($val)
        {
            $this->justify=$val;
        }
        
        public function getJustify()
        {
            return $this->justify;
        }
        
	/**
	* @ManyToOne(targetEntity="OEMREncounter", inversedBy="billingEntries")
	* @JoinColumn(name="encounter", referencedColumnName="encounter")
	*/        
        protected $encounter;
        
        /**
	 * @Column(type="integer")
	 */
        protected $provider_id;
        
        public function setProvider_id($val)
        {
            $this->provider_id=$val;
        }
        
        /**
	 * @Column(type="integer")
	 */
        protected $units;
        
        public function setUnits($val)
        {
            $this->units=$val;
        }
        

        /**
	 * @Column(type="integer")
	 */
        protected $authorized;
        
        public function setAuthorized($val)
        {
            $this->authorized=$val;
        }
        
	/**
	 * @Column(type="string") 
 	 */
        protected $groupname;        
        
        public function setGroupname($val)
        {
            $this->groupname=$val;
        }

        /**
	 * @Column(type="integer")
	 */
        protected $billed;
        
        public function setBilled($val)
        {
            $this->billed=$val;
        }

        
        public function getBilled()
        {
            return $this->billed;
        }
        
        
        /**
	 * @Column(type="integer")
	 */
        protected $activity;
        
        public function setActivity($val)
        {
            $this->activity=$val;
        }
 
        
	/**
	* @ManyToOne(targetEntity="User")
	* @JoinColumn(name="user", referencedColumnName="id")
	*/        
        protected $user;
        
        
	/**
	 * @Column(type="string") 
 	 */
        protected $modifier;
        
        public function getModifier()
        {
            return $this->modifier;
        }

        public function setModifier($val)
        {
            $this->modifier=$val;
        }        

    /** @PreUpdate */
    public function preUpdateEventHandler()
    {
     
        // Prevent update of locked
        if($this->getBilled()==1)
        {
            throw new \Exception("Cannot Update Entry, already billed");
        }
    }        
}
?>


