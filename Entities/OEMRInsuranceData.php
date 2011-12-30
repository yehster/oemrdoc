<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="insurance_data")
 */
class OEMRInsuranceData{
    
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
	* @ManyToOne(targetEntity="Patient", inversedBy="insurance_data")
	* @JoinColumn(name="pid", referencedColumnName="pid")
	*/
        protected $patient;


       /**
	 * @Column(type="string") 
 	 */
        protected $plan_name;
        
        public function getPlan_name()
        {
            return $this->plan_name;
        }
        
        /**
	 * @Column(type="string") 
 	 */
	protected $provider;
	// the value of the code entry

        public function setProvider($val)
        {
            $this->provider=$val;
        }
        
        public function getProvider()
        {
            return $this->provider;
        }
        
        /**
	 * @Column(type="string") 
 	 */
	protected $type;
 
        public function getType()
        {
            return $this->type;
        }

        public function setType($val)
        {
            if(($val==="primary")|| ($val==="secondary") || ($val==="tertiary"))
            {
                $this->type=$val;            
            }
        }
}
?>


