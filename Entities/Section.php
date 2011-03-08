<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class Section extends DocumentEntry
{
        const classtype = "Section";
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
            return "DIV";
        }


} 

 ?>