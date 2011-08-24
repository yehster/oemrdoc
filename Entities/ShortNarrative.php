<?php
namespace library\doctrine\Entities;
require_once('DocumentEntry.php');
require_once('Narrative.php');

/**
  * @Entity
  */
class ShortNarrative extends Narrative
{

        const classtype = "ShortNarrative";
        public function getType()
        {
            return self::classtype;
        }


}
?>