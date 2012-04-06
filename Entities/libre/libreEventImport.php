<?php
namespace library\doctrine\Entities\libre;
include_once('libreEvent.php');

/**
  * @Entity
 */
class libreEventImport extends libreEvent
{

        const classtype = "Import";
        public function getType()
        {
            return self::classtype;
        }    
}

?>
