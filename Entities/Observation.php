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

        /** @Column(type="string",name="attr1") */
        protected $value;
 
        public function setValue($val)
        {
            $this->value=$val;
        }
        
        public function getValue()
        {
            return $this->value;
        }
 }
 ?>