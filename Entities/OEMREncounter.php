<?php
namespace library\doctrine\Entities;

/** @Entity
 *  @Table(name="form_encounter")
 */
class OEMREncounter {

    public function __construct($pat)
    {
	$this->date = new \DateTime();
        $this->patient = $pat;
    }    
    
        /**
	 * @Id
	 * @Column(name="encounter",type="integer")
         * @GeneratedValue
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
	* @OneToMany(targetEntity="OEMRBillingEntry", mappedBy="encounter" )
	* @OrderBy({"code_type" = "ASC","code"="ASC"})
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
