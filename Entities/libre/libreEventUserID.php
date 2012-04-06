<?php
namespace library\doctrine\Entities\libre;
include_once('libreEvent.php');

/**
  * @Entity
 */
class libreEventUserID extends libreEvent
{
        const classtype = "UserID";
        public function getType()
        {
            return self::classtype;
        }    
    
}

?>
