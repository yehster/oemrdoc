<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class QuantitativeEntry extends DocumentEntry
{
    
        const classtype = "QuantitativeEntry";
        public function getType()
        {
            return self::classtype;
        }
        
    /**
     * @column(type="float",name="num1")
     */
    protected $value;
    
    public function setValue($val,$auth)
    {
        $this->auth = $auth;
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
        
        /** @Column(type="string",name="attr1") */
        protected $units;

        public function getUnits()
        {
            return $this->units;
        }


        public function setUnits($val)
        {
            $this->units=$val;
        }
        public function getText()
        {
            return $this->text.":".$this->value.$this->units;
        }
}

?>
