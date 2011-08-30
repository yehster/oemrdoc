<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="dct_vocab_mappings")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="relationship", type="string")
 *  @DiscriminatorMap({"form" = "FormEntry", "option" = "Option", "comp" = "VocabComponent"})*/
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
	private $created;

	/**
	 * @Column(type="datetime")
	 */
	private $modified;

        /**
         * @Column(type="string")
         */
        protected $source_code;

        public function getSource_code()
        {
            return $this->source_code;
        }

        /**
         * @Column(type="string")
         */
        protected $source_code_type;

        public function getSource_code_type()
        {
            return $this->source_code_type;
        }

        /**
         * @Column(type="string")
         */
        protected $target_code;
        public function getTarget_code()
        {
            return $this->target_code;
        }

        /**
         * @Column(type="string")
         */
        protected $target_code_type;
        
        public function getTarget_code_type()
        {
            return $this->target_code_type;
        }

        /**
         * @Column(type="string")
         */
        protected $text;

        public function getText()
        {
            return $this->text;
        }

        /**
         * @Column(type="string")
         */
        protected $classification;
        /**
         * types of classifications
         * FormEntries
         * { normal, abnormal, exclusive, multiple, text }
         */
        
        
        public function getClassification()
        {
            return $this->classification;
        }

        public function setClassification($val)
        {
            $this->classification=$val;
        }

        /**
         * @Column(type="integer")
         */
        protected $seq;

        public function setSeq($val)
        {
            $this->seq=$val;
        }

        public function getSeq()
        {
            return $this->seq;
        }
        
              /** @Column(type="string",name="attr1") */
      protected $property;    
      
      public function setProperty($val)
      {
          $this->property=$val;
      }

      public function getProperty()
      {
          return $this->property;
      }

}
?>
