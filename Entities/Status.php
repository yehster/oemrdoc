<?php
namespace library\doctrine\Entities;

 /**
  * @Entity
  * @Table(name="dct_statuses")
  */
class Status
{
    public function __construct($md,$pat,$auth)
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->patient = $pat;
        $this->author = $auth;
	$this->startdate = new \DateTime();
	$this->enddate = new \DateTime();

    }
    const classtype = "Status";
    public function getType()
    {
        return self::classtype;
    }
    /**
     * @Id
     * @Column(type="string")
     */
    private $uuid;

    public function getUUID()
    {
        return $this->uuid;
    }

    /**
     * @Column(type="datetime")
     */
    private $created;


    /**
     * @Column(type="datetime")
     */
    private $modified;

    /**
     * @Column(type="datetime")
     */
    private $startdate;


    /**
     * @Column(type="datetime")
     */
    private $enddate;


    /**
      * @ManyToOne(targetEntity="Document", inversedBy="items", cascade={"persist"})
      * @JoinColumn(name="document_id", referencedColumnName="uuid")
      */
    private $DocumentEntry;

} 

 ?>