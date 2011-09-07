<?php
namespace library\doctrine\Entities;
require_once('DocumentEntry.php');
 /**
  * @Entity
  */
class MedicationSIG extends DocumentEntry
{
        const classtype = "MedicationSIG";
        public function getType()
        {
            return self::classtype;
        }
    
    
        /** @Column(type="string",name="attr1") */
        protected $route;

        public function setRoute($val)
        {
            $this->route=$val;
        }

        public function getRoute()
        {
            return $this->route;
        }
        
        
        /** @Column(type="string",name="attr2") */
        protected $frequency;

        public function setFrequency($val)
        {
            $this->frequency=$val;
        }

        public function getFrequency()
        {
            return $this->frequency();
        }
        
        /** @Column(type="string",name="attr3") */
        protected $units;
        
        public function getUnits()
        {
            return $this->units;
        }
        
        public function setUnits($val)
        {
            $this->units=$val;
        }
        
        /**
         * @column(type="float",name="num1")
         */
        protected $quantity;
        
        
        public function setQuantity($val)
        {
            $this->quantity=$val;
        }
        
        public function getQuantity()
        {
            return $this->quantity;
        }
        
        
        
        public function updateFromObject($sigObject)
        {
            $this->setQuantity($sigObject->getQuantity());
            $this->getQuantity($sigObject->getUnits());            
            $this->setRoute($sigObject->getRoute());
            $this->getFrequency($sigObject->getFrequency());

        }
}
?>