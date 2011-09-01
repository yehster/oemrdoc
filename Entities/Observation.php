<?php
namespace library\doctrine\Entities;
require_once("DocumentEntry.php");
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
            if($this->value=="Y")
            {
                return $this->text;    
            }
            elseif($this->value=="N")
            {
                return "NO ".$this->text;
            }
        }

        public function similar($comp)
        {
            if($comp->getvocabID()!=$this->getvocabID())
            {
                return false;
            }
            else
            {
                return parent::similar($comp);
            }
        }



 }
 ?>