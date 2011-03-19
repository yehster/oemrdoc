<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="dct_vocab_mappings")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="relationship", type="string")
 *  @DiscriminatorMap({"form" = "FormEntry"})*/
class VocabMapping {



    public function __construct($text,$sourceCode,$sourceType,$targetCode,$targetType)
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->text=$text;
        $this->source_code=$sourceCode;
        $this->source_code_type=$sourceType;
        $this->target_code=$targetCode;
        $this->target_code_type=$targetType;

    }

        public function getRelationship()
        {
            return $this->relationship;
        }

        public function setRelationship($val)
        {
            $this->relationship = $val;
        }
        
      	/**
	 * @Id
	 * @Column(type="string")
	 */
	protected $uuid;

	/**
	 * @Column(type="datetime")
	 */
	private $created;

	/**
	 * @Column(type="datetime")
	 */
	private $modified;

        /**
         * @Column(type="string")
         */
        protected $source_code;

        /**
         * @Column(type="string")
         */
        protected $source_code_type;

        /**
         * @Column(type="string")
         */
        protected $target_code;

        /**
         * @Column(type="string")
         */
        protected $target_code_type;

        /**
         * @Column(type="string")
         */
        protected $text;

        public function getText()
        {
            return $this->text;
        }




}
?>
