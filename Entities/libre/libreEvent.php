<?php
namespace library\doctrine\Entities\libre;

/** @Entity
 *  @Table(name="libre_event")
 */
class libreEvent {
    public function __construct($libreFile,$ei,$suc)
    {
        $this->uuid=uuid_create();
        $this->created = new \DateTime();
        $this->file=$libreFile;
        $this->event_info=$ei;
        $this->success = $suc;
        $libreFile->addEvent($this);
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
    * @ManyToOne(targetEntity="libreFile", inversedBy="events", cascade={"persist"})
    * @JoinColumn(name="file", referencedColumnName="filename")
    */
    protected $file;

    /**
    * @Column(type="string") 
    */
    protected $event_info;

    /**
    * @Column(type="boolean") 
    */
    protected $success;
}

?>
