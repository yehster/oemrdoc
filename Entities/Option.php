<?php
namespace library\doctrine\Entities;
include_once('VocabMapping.php');

 /**
  * @Entity
  */
class Option extends VocabMapping {

    public function getType()
    {
        return "Option";
    }
}
?>
