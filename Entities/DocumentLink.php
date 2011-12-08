<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class DocumentLink  extends DocumentEntry
{
    // This class is a symbolic link
    public function __construct($md,$pat,$auth,$le)
    {
        parent::__construct($md,$pat,$auth);
        $this->linked_entry=$le;
    }

        const classtype = "DocumentLink";
        public function getType()
        {
            return self::classtype;
        }    
	
    /**
      * @ManyToOne(targetEntity="DocumentEntry")
      * @JoinColumn(name="attr1", referencedColumnName="uuid")
      */
	protected $linked_entry;
        
        public function getLinkedEntry()
        {
            return $this->linked_entry;
        }
        
        public function copy($auth)
        {
            return $this->linked_entry->copy($auth);
        }
}

?>
