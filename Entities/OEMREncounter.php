<?php
namespace library\doctrine\Entities;

/** @Entity
 *  @Table(name="form_encounter")
 */
class OEMREncounter {

    public function __construct($pat,$dt,$encID,$prov,$sens)
    {
	$this->date = $dt;
        $this->patient = $pat;
        $this->encounter=$encID;
        $this->sensitivity=$sens;
        $this->referral_source="";
        $this->provider_id=$prov;
    }    
    
    
        /**
         * @Column(type="integer")
         */
        protected $id;
        
        public function getID()
        {
            return $this->id;
        }
    
        /**
         * @ID
	 * @Column(type="integer")
	 */
	protected $encounter;    

        public function getEncounter()
        {
            return $this->encounter;
        }

        /**
	* @ManyToOne(targetEntity="Patient")
	* @JoinColumn(name="pid", referencedColumnName="pid")
	*/
	protected $patient;        

	/** 
	 * @Column(type="datetime") 
	 */
	protected $date;        
        
        /**
         * @Column(type="integer")
         */      
        protected $provider_id;   
        
        public function getProvider_id()
        {
            return $this->provider_id;
        }
        
	/** 
	 * @Column(type="datetime") 
	 */
        protected $onset_date;
        
        
	/** 
	 * @Column(type="string") 
	 */
        protected $reason;
        
        public function setReason($val)
        {
            $this->reason=$val;
        }
        
	/** 
	 * @Column(type="string") 
	 */
        protected $facility;
        
        /**
         * @Column(type="integer")
         */  
        protected $pc_catid;
        
        public function setCatid($val)
        {
            $this->pc_catid=$val;
        }
        
        /**
         * @Column(type="integer")
         */  
        protected $facility_id;
        
        function setFacility_id($val)
        {
            $this->facility_id=$val;
        }
        
        /**
         * @Column(type="integer")
         */  
        protected $billing_facility;

        function setBilling_facility($val)
        {
            $this->billing_facility=$val;
        }
        
        /** 
	 * @Column(type="string") 
	 */
        protected $sensitivity;
        
        /** 
	 * @Column(type="string") 
	 */        
        protected $referral_source;
        
        /**
	* @OneToMany(targetEntity="OEMRBillingEntry", mappedBy="encounter" )
	* @OrderBy({"code_type" = "ASC","date"="ASC"})
	*/
        protected $billingEntries;
        

        
        public function getBillingEntries()
        {
            return $this->billingEntries;
        }
        
        public function isBilled()
        {
            foreach($this->billingEntries as $be)
            {
                if($be->getBilled()>0)
                {
                    return true;
                }
            }
            return false;
        }
        
        protected $allCodes=null;
        public function isCodeBilled($code)
        {
            if($this->allCodes==null)
            {
                $this->allCodes="";
                foreach($this->billingEntries as $be)
                {
                    $this->allCodes.="[".$be->getCode()."]";
                }                
            }
            return (strpos($this->allCodes,"[".$code."]")!==false);
        }

}
        
?>
