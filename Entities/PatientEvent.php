<?php
namespace library\doctrine\Entities;

/** 
 * @Entity 
 * @Table(name="dct_patient_events")
 */
class PatientEvent {
    
    public function __construct($pat,$auth,$pes)
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
	$this->time = new \DateTime();
        $this->patient = $pat;
        $this->author = $auth;
        $this->status=$pes;
    }
    
    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;

    public function getUUID()
    {
        return $this->uuid;
    }

    /** 
     * @Column(type="datetime") 
     */
    protected $created;
    
    public function getCreated()
    {
        return $this->created;
    }

    /** 
     * @Column(type="datetime") 
     */
    protected $modified;

    public function getModified()
    {
        return $this->modified;
    }

    /** 
     * @Column(type="datetime") 
     */
    protected $time;

    public function getTime()
    {
        return $this->time;
    }

    
    /**
    * @ManyToOne(targetEntity="Patient")
    * @JoinColumn(name="patient_id", referencedColumnName="pid")
    */
    protected $patient;

    public function getPatient()
    {
        return $this->patient;
    }
    
    /**
     * @Column(type="string") 
     */
    protected $author;
    // Who wrote this entry.  If an entry is modified, the author will be updated, this could be an internal user or an external source.
    // may need to expand this entry to additional fields to accommodate HL7 source info. if the author isn't specified, assume the parent entry's
    // author is the author.

    public function getAuthor()
    {
        return $this->author;
    }

    
    /**
    * @ManyToOne(targetEntity="PatientEventStatus")
    * @JoinColumn(name="status_id", referencedColumnName="id")
    */
    protected $status;

    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @Column(type="string") 
     */
    protected $for_user;
    // which user this event is relevant for.
}

?>
