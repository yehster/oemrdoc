<?php
namespace library\doctrine\Entities\libre;

/** @Entity
 *  @Table(name="libre_event")
 */
class libreEvent {
    public function __construct($libreFile,$ei,$suc,$msg)
    {
        $this->uuid=uuid_create();
        $this->created = new \DateTime();
        $this->file=$libreFile;
        $this->event_info=$ei;
        $this->success = $suc;
        $this->message = $msg;
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
    
    public function successful()
    {
        return $this->success;
    }
    /**
    * @Column(type="string") 
    */
    protected $message;
    
    public function getXMLDOM()
    {
        if(!$this->event_info=="XML")
        {
            return null;
        }
        if(!$this->successful())
        {
            return null;
        }
        $fullpath=$this->message.$this->file->getFilename().".xml";
        echo $fullpath;
    }
}

?>
