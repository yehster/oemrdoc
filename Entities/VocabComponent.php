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

      /** @Column(type="string",name="attr1") */
      protected $property;    
      
      public function setProperty($val)
      {
          $this->property=$val;
      }

      public function getProperty()
      {
          return $this->property;
      }
      
}
?>
