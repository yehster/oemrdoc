<?php
namespace library\doctrine\Entities;
include_once('VocabMapping.php');

 /**
  * @Entity
  */
class FormEntry extends VocabMapping {

    public function getType()
    {
        return "FormEntry";
    }

}
?>
