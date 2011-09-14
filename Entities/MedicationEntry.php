<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class MedicationEntry extends DocumentEntry
{
    public function __construct($md,$pat,$auth)
    {
        parent::__construct($md,$pat,$auth);

    }

    public function copy($auth)
    {
        $retVal=parent::copy($auth);
        $retVal->rxcui = $this->rxcui;
        $retVal->rxaui = $this->rxaui;
        return $retVal;
    }
        const classtype = "MedicationEntry";
        public function getType()
        {
            return self::classtype;
        }

        /** @Column(type="string",name="attr1") */
        protected $rxcui;

        public function setRXCUI($val)
        {
            $this->rxcui=$val;
        }
        
        public function getRXCUI()
        {
            return $this->rxcui;
        }

	/**
	  * @OneToOne(targetEntity="RXNConcept")
	  * @JoinColumn(name="attr2", referencedColumnName="RXAUI")
	  */
	protected $rxaui;

        public function getRXAUI()
        {
            return $this->rxaui;
        }
        
        public function setRXAUI($val)
        {
            $this->rxaui=$val;
        }

        public function similar($comp)
        {
            if(parent::similar($comp))
            {
                if ($comp->getText()==$this->getText())
                {
                    return true;
                }
            }
            return false;
        }
        
        public function getSIGs()
        {
            $retval=array();
            foreach($this->getItem()->getItems() as $item)
            {
                if($item->getEntry()->getType()=="MedicationSIG")
                {
                    $retval[]=$item->getEntry();
                }
            }
            if(count($retval))
            {
                $retval[]=new MedicationSIG;
                $this->getItem()->addEntry($retval,1);
            }
            return $retval;
        }
        

}
?>
