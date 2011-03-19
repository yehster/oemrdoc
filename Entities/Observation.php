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
        protected $metadataID;

        public function setMetadataID($val)
        {
            $this->metadataID=$val;
        }

        public function getMetadataID()
        {
            return $this->metadataID;
        }



 }
 ?>