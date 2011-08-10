<?php
namespace library\doctrine\Entities;
  /**
  * @Entity
  */

class Nominative extends DocumentEntry
 {
        const classtype = "Nominative";
        public function getType()
        {
            return self::classtype;
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
