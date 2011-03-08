<?php
namespace library\doctrine\Entities;
  /**
  * @Entity
  */
 class Observation extends DocumentEntry
 {
        const classtype = "Observation";
        public function getType()
        {
            return self::classtype;
        }

 }
 ?>