<?php

namespace library\doctrine\Entities\OENR;
/** @Entity
 *  @Table(name="forms")
 */
class OEMRForm {

    public function __construct($pat)
    {
	$this->date = new \DateTime();
        $this->patient = $pat;
    }    
    
    /**
      * @Id
      * @Column(type="integer")
      * @GeneratedValue
      */     protected $id;

     /** 
       * @Column(type="datetime") 
       */     
     protected $date;
     
     protected $encounter;
     
     /*
      * @Column(type="string")
      */
     protected $form_name;
     
     /*
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
     protected $user;
     
     /*
      * @Column(type="string")
      */
     protected $groupname;
     
     /*
      * @Column(type="integer")
      */
     protected $authorized;
     
     /*
      * @Column(type="integer")
      */
     protected $deleted;
     
     /*
      * @Column(type="string")
      */
     protected $formdir;
     
}
?>