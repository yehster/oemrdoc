<?php
namespace library\doctrine\Entities;

/** 
 * @Entity 
 * @Table(name="dct_patient_events_statuses")
 */
class PatientEventStatus
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */    
    protected $id;
    
    /**
     * @Column(type="string") 
     */    
    protected $text;
}
?>
