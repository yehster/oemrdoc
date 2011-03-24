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
            $this->modified = new \DateTime();
            $this->value=$val;
        }
        
        public function getValue()
        {
            return $this->value;
        }

      /** @Column(type="string",name="attr2") */
        protected $vocabID;

        public function setvocabID($val)
        {
            $this->vocabID=$val;
        }

        public function getvocabID()
        {
            return $this->vocabID;
        }

        public function getText()
        {
            return $this->text."=".$this->value;
        }





 }
 ?>