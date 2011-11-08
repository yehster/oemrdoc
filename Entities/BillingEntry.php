<?php

namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 
/**
  * @Entity
  */
class BillingEntry extends DocumentEntry
{


    public function __construct($md,$pat,$auth)
    {
        parent::__construct($md,$pat,$auth);
        $OEMRBillingEntry=new OEMRBillingEntry();
        $OEMRBillingEntry->setPID($pat->getPID());
    }
    
	/**
	  * @OneToOne(targetEntity="OEMRBillingEntry",cascade={"persist", "remove"})
	  * @JoinColumn(name="OEMRID", referencedColumnName="id")
	  */
	protected $OEMRBillingEntry;

        
}

?>
