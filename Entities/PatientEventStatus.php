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
    
    public function getID()
    {
        return $this->id;
    }

    /**
     * @Column(type="string") 
     */
    protected $text;
    
    
    public function getText()
    {
        return $this->text;
    }
    /**
     * @Column(type="integer") 
     */    
    protected $seq;
    
}
?>
