<?php
namespace library\doctrine\Entities;
require_once("DocumentMetadata.php");
/** @Entity */
class NarrativeMetadata extends DocumentMetadata
{
    	/**
	 * @Column(type="string")
	 */
         private $code;

        public function setCode($c,$ct)
         {
            $this->code = $c;
            $this->code_type=$ct;
         }
         
    	/**
	 * @Column(type="string")
	 */
         private $code_type;

        public function setCode_type($ct)
        {
            $this->code_type = $ct;
        }

        public function makeEntry(Patient $pat,$auth)
        {
            $retVal = new Narrative($this,$pat,$auth);
            $retVal->setCode($this->code,$this->code_type);
            return $retVal;
        }

}
?>
