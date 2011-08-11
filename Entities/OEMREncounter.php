<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="form_encounter")
 *  @InheritanceType("SINGLE_TABLE")
 */
class OEMREncounter {

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
}
?>
