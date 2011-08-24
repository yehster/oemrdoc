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

} 

 ?>