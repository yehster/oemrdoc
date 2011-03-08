<?php

namespace library\doctrine\Entities;
include_once ('DocumentMetadata.php');

/** @Entity */
class SectionHeading extends DocumentMetadata
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

         public function getCode()
         {
             return $this->code;
         }

    	/**
	 * @Column(type="string")
	 */
         private $code_type;


         public function getCode_type()
         {
             return $this->code_type;
         }

         
         public function setCode_type($ct)
         {
            $this->code_type = $ct;
         }
        public function makeEntry($pat,$auth)
        {
            $retVal = new Section($this,$pat,$auth);
            $retVal->setText($this->getlongDesc(),$auth);
            $retVal->setCode($this->code,$this->code_type);
            return $retVal;
        }

}

?>
