<?php

namespace library\doctrine\Entities;

/** @Entity 
 *	@Table(name="dct_document_items")
 */
 class DocumentItem
 {
	// This class provides referencing and linking to document entries.  The true information is located in a document entry object.
        public function __construct($root,$md,$pat,$auth)
	{

		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
		$this->uuid=uuid_create();
		$this->created = new \DateTime();
		$this->modified = new \DateTime();
                $this->root=$root;
                if($md!=null)
                {
                    $this->entry = $md->makeEntry($pat,$auth);

                    foreach($md->getItems() as $value)
                    {
                        $newItem = new DocumentItem($root,$value->getMetadata(),$pat,$auth);
                        $this->addItem($newItem);
                    }
                }

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
	  * @ManyToOne(targetEntity="Document", inversedBy="items", cascade={"persist","remove"})
	  * @JoinColumn(name="document_id", referencedColumnName="uuid")
	  */
	private $document;

	public function setDocument($val)
	{
	  $this->document=$val;
	}

	/**
	* @ManyToOne(targetEntity="Document")
	* @JoinColumn(name="root_id", referencedColumnName="uuid")
	*/
        private $root;

        public function setRoot($val)
        {
            $this->root = $val;
        }

        public function getRoot()
        {
            return $this->root;
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
	  * @ManyToOne(targetEntity="DocumentItem", inversedBy="items", cascade={"persist"})
	  * @JoinColumn(name="parent_id", referencedColumnName="uuid")
	  */
	private $parent;

	public function setParent($par)
	{
	  $this->parent=$par;
	}

        public function getParent()
        {
            return $this->parent;
        }

      /**
	* @OneToMany(targetEntity="DocumentItem", mappedBy="parent", cascade={"persist","remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
	private $items;
	// These are the child items of a parent document item

        public function addEntry($entry,$seq=-1)
        {

            $newItem = new DocumentItem($this->root,null,$entry->getPatient(),$entry->getAuthor());
            $newItem->setEntry($entry);
            $entry->setItem($newItem);
            $this->addItem($newItem,$seq);
            return $newItem;
        }

	public function addItem($obj,$seq=-1)
	{
		$obj->setParent($this);
                $lastItem=$this->items->last();
                if($lastItem!=null)
                {
                    $lastSeq=$lastItem->getSeq();
                }
                else
                {
                    $lastSeq=0;
                }
                if($seq!==-1)
                {
                    $obj->setSeq($seq);
                    if($seq>=$lastSeq)
                    {
                        $this->items->add($obj);
                    }
                    else
                    {
                        // Otherwise we have to find the correct position for this item.
                        $this->items->add($obj);
                        for($idx=count($this->items)-2;(($idx>=0) && ($this->items[$idx]->getSeq()>=$seq)) ;$idx--)
                        {
                            $this->items[$idx+1]=$this->items[$idx];
                            $this->items[$idx]=$obj;
                        }
                    }
                }
                else
                {
                    $this->items->add($obj);
                    $obj->setSeq($lastSeq+1);
                }
	}

        public function getItems()
        {
            return $this->items;
        }

	/**
	  * @OneToOne(targetEntity="DocumentEntry", cascade={"persist","remove"}, inversedBy="item")
	  * @JoinColumn(name="entry_id", referencedColumnName="uuid")
	  */
	protected $entry;

        public function getEntry()
	{
		return $this->entry;
	}


        public function setEntry($entry)
	{
		$this->entry=$entry;
                $entry->setItem($this);
	}

	/**
	 * @Column(type="integer") 
 	 */
	private $seq;
	// The order of the items
	public function setSeq($val)
	{
            if($this->isLocked()){return;}
            $this->seq=$val;
	}
	
	public function getSeq()
	{
            return $this->seq;
	}

                /**
         * @Column(type="datetime")
         */
        protected $locked;

        public function getLocked()
        {
            
            return $this->locked;
        }
        
        public function isLocked()
        {
            return !is_null($this->locked);
        }

        public function lock($time)
        {
            if(!is_null($time))
            {
                $this->locked = $time;
            }
        }

 }

 ?>
