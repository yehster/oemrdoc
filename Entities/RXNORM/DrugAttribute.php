<?php
namespace library\doctrine\Entities\RXNORM;
/** 
 * @Entity
 *  @Table(name="dct_drug_attributes")
 */
class DrugAttribute
{
    	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	private $id;

        public function getID()
        {
            return $this->id;
        }
        
	/**
	 * @Column(type="string") 
 	 */
        protected $rxcui;

	/**
	 * @Column(type="string") 
 	 */
        protected $ATN;

        public function getATN()
        {
            return $this->ATN;
        }
	/**
	 * @Column(type="string") 
 	 */       
        protected $ATV;
        
        public function getATV()
        {
            return $this->ATV;
        }
}
?>
