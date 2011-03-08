<?php
namespace library\doctrine\Entities;

/** @Entity
 *  @Table(name="dct_observation_metadata")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"exam" = "ExamFinding", "sym" = "Symptom"})
 */
class ObservationMetadata
{
    public function __construct($c,$ct,$txt)
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->code=$c;
        $this->code_type=$ct;
        $this->text=$txt;
    }

      	/**
	 * @Id
	 * @Column(type="string")
	 */
	private $uuid;
        
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
        private $code;

        /**
         * @Column(type="string")
         */
        private $code_type;


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
        /* is this typically a "normal" or and "abnormal" finding */

        public function getClassification()
        {
            return $this->classification;
        }

        public function setClassification($val)
        {
            $this->classification = $val;
        }
        
}


?>
