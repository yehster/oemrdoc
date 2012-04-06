<?php
namespace library\doctrine\Entities\libre;

/** @Entity
 *  @Table(name="libre_event")
*  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="event_info", type="string")
 *  @DiscriminatorMap({"XML" = "libreEventXML",
 *                     "PATID" = "libreEventPatientID",
 *                     "USERID" = "libreEventUserID",
 *                     "IMPORT" = "libreEventImport"
 *  }) 
*/
class libreEvent {
    public function __construct($libreFile,$suc,$msg)
    {
        $this->uuid=uuid_create();
        $this->created = new \DateTime();
        $this->file=$libreFile;
        $this->success = $suc;
        $this->message = $msg;
        $libreFile->addEvent($this);
    }

    const classtype = "libreEvent";
    public function getType()
    {
        return self::classtype;
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

    public function getFile()
    {
        return $this->file;
    }
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
    
    public function getMessage()
    {
        return $this->message;
    }

    /**
    * @Column(type="integer") 
    */
    protected $seq;
    
    public function getSeq()
    {
        return $this->seq;
    }    

    public function setSeq($val)
    {
        $this->seq=$val;
    }    
    
}

?>
