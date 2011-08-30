<?php
namespace library\doctrine\Entities;
include_once('VocabMapping.php');

 /**
  * @Entity
  */
class VocabComponent extends VocabMapping
{
    public function getType()
    {
        return "VocabComponent";
    }

      
}
?>
