<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class Narrative extends DocumentEntry
{

        const classtype = "Narrative";
        public function getType()
        {
            return self::classtype;
        }

        public function getTagInfo()
        {
            $info = " id='".$this->getUUID()."' "." class='".self::classtype."'";
            return $info;
        }

        public function getTag()
        {
            return "TEXTAREA";
        }

}

?>
