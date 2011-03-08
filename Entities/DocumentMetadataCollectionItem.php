<?php

namespace library\doctrine\Entities;

/** @Entity 
 *  @Table(name="dct_document_metadata_collection_items")
 */
 class DocumentMetadataCollectionItem
{
    public function __construct($md)
    {

	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->metadata = $md;
    }
 
	/**
	 * @Id 
	 * @Column(type="string") 
	 */
	protected $uuid;

	/** 
	 * @Column(type="datetime") 
	 */
	protected $created;


	/** 
	 * @Column(type="datetime") 
	 */
	protected $modified;


	/** 
	 * @Column(type="integer") 
	 */
	protected $seq;
	// The order of the items

	public function setSeq($val)
	{
		$this->seq=$val;
	}

	/**
	  * @ManyToOne(targetEntity="DocumentMetadata")
	  * @JoinColumn(name="parent_id", referencedColumnName="uuid")
	  */
        protected $parent;

        public function setParent($obj)
        {
            $this->parent = $obj;
        }


	/**
	  * @OneToOne(targetEntity="DocumentMetadata", cascade={"persist"})
	  * @JoinColumn(name="metadata_id", referencedColumnName="uuid")
	  */
	protected $metadata;

        public function getMetadata()
        {
            return $this->metadata;
        }



}
?>
