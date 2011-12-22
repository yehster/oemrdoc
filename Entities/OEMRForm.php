<?php

namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="forms")
 */
class OEMRForm {

    public function __construct($pat,$user,$enc,$fid,$fname,$fdir,$gn="Default")
    {
	$this->date = new \DateTime();
        $this->patient = $pat;
       $this->encounter=$enc;
       $this->user=$user;
       $this->form_id=$fid;
        $this->form_name=$fname;
        $this->formdir=$fdir;
        $this->groupname=$gn;
        $this->authorized=1;
        $this->deleted=0;
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
     
     /**
     * @Column(type="string") 
     */
     protected $groupname;  
     
     /**
       * @Column(type="integer")	 
       */
     protected $authorized;  
     
     /**
       * @Column(type="integer")	 
       */
     protected $deleted;       
}
?>
