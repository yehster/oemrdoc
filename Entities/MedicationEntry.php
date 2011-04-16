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

	/**
	  * @OneToOne(targetEntity="RXNConcept")
	  * @JoinColumn(name="attr2", referencedColumnName="RXAUI")
	  */
	protected $RXAUI;

        public function setRXAUI($val)
        {
            $this->RXAUI=$val;
        }


}
?>
