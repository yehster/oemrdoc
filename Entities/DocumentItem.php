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

        public function addEntry($entry)
        {
            $newItem = new DocumentItem($this->root,null,null,null);
            $newItem->setEntry ($entry);
            $this->addItem($newItem);
            return $newItem;
        }

	public function addItem($obj)
	{
		$this->items->add($obj);
		$obj->setParent($this);
		$obj->setSeq($this->items->count());
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
	}


	/**
	 * @Column(type="integer") 
 	 */
	private $seq;
	// The order of the items
	public function setSeq($val)
	{
		$this->seq=$val;
	}
	

 }

 ?>
