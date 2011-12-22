<?php

namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="forms")
 */
class OEMRForm {

    public function __construct($pat,$user,$enc,$fid,$fname,$fdir)
    {
	$this->date = new \DateTime();
        $this->patient = $pat;
       $this->encounter=$enc;
       $this->user=$user;
       $this->form_id=$fid;
        $this->form_name=$fname;
        $this->formdir=$fdir;
    }    
    
    /**
      * @Id
      * @Column(type="integer")
      * @GeneratedValue
      */     
    protected $id;

     /** 
       * @Column(type="datetime") 
       */     
     protected $date;
     
     
     /**
        * @Column(type="integer")	 
        */
     protected $encounter;
     
     function setEncounter($val)
     {
         $this->encounter = $val;
     }
     
    /**
     * @Column(type="string") 
     */
     protected $user;     
     

     /**
       * @Column(type="integer")	 
       */
     protected $form_id;     

     /**
       * @ManyToOne(targetEntity="Patient")
       * @JoinColumn(name="pid", referencedColumnName="pid")
       */
     protected $patient;     

     
    /**
     * @Column(type="string") 
     */
     protected $form_name;     

    /**
     * @Column(type="string") 
     */
     protected $formdir;         
}
?>
