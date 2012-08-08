<?php
namespace library\doctrine\Entities\IEMR;


/**
  * @Entity
  * @Table(name="iemr_codes")
  */

class IEMRCode {
    //put your code here
    
   /**
    * @Id 
    * @Column(type="string") 
    */
    protected $code;
    
    public function getCode()
    {
        return $this->code;
    }
    
   /**
    * @Column(type="string") 
    */
    protected $description;

    public function getDescription()
    {
        return $this->code;
    }
    
}

?>
