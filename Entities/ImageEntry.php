<?php

namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class ImageEntry extends DocumentEntry
{
        const classtype = "ImageEntry";
        public function getType()
        {
            return self::classtype;
        }

} 

?>
