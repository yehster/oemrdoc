<?php
namespace library\doctrine\Entities\libre;
include_once('libreEvent.php');

/**
  * @Entity
 */
class libreEventPatientID extends libreEvent
{
        const classtype = "PatientID";
        public function getType()
        {
            return self::classtype;
        }    
    
}

?>
