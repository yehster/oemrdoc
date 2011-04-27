<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class QuantitativeEntry extends DocumentEntry
{
    /**
     * @column(type="float",name="num1")
     */
    protected $value;
    
    public function setValue($val,$auth)
    {
        $this->modified = new \DateTime;
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
        
        public function getText()
        {
            return $this->text.":".$this->value;
        }
}

?>
