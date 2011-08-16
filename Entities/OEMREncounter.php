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
	protected $id;    

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
         * @column(type="integer")
         */
        protected $provider_id;
        
}
?>
