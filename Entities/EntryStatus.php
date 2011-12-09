<?php
namespace library\doctrine\Entities;

/** 
 * @Entity 
 * @Table(name="dct_entry_statuses")
 */
class EntryStatus {
    public function __construct($entry,$auth, $stat)
    {
	$this->uuid=uuid_create();
        $this->author = $auth;       
        
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->entry=$entry;
        $this->status = $stat;
        
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
     * @ManyToOne(targetEntity="DocumentEntry", inversedBy="statusHistory")
     * @JoinColumn(name="entry_id", referencedColumnName="uuid")
     */
    protected $entry;

    public function getEntry()
    {
            return $this->entry;
    }    

    /**
     * @Column(type="integer") 
     */
    protected $status;

    public function getStatus()
    {
        return $this->status;
    }    
    
    public function getText()
    {
        return $this->status > 0 ? "Active" : "Discontinued";
    }
}
    

?>
