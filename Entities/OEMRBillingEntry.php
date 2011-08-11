<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="billing")
 *  @InheritanceType("SINGLE_TABLE")
 */
class OEMRListItem {
//`billing`.`id`, `billing`.`date`, `billing`.`code_type`, `billing`.`code`, `billing`.`pid`, `billing`.`provider_id`, `billing`.`user`, `billing`.`groupname`, `billing`.`authorized`, `billing`.`encounter`, `billing`.`code_text`, `billing`.`billed`, `billing`.`activity`, `billing`.`payer_id`, `billing`.`bill_process`, `billing`.`bill_date`, `billing`.`process_date`, `billing`.`process_file`, `billing`.`modifier`, `billing`.`units`, `billing`.`fee`, `billing`.`justify`, `billing`.`target`, `billing`.`x12_partner_id`, `billing`.`ndc_info`

    /**
	 * @Id
	 * @Column(type="integer")
         * @GeneratedValue
	 */
	protected $id;

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
        
}
?>

