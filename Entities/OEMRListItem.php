<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="lists")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="type", type="string")
 *  @DiscriminatorMap({"medical_problem" = "OEMRProblem", "allergy" = "OEMRAllergy", "medication" = "OEMRMedication"})
 */
class OEMRListItem {
    //put your code here

    public function __construct($pat,$title)
    {
        $this->outcome=0;
        $this->date=new \DateTime();
        $this->title=$title;
        $this->patient = $pat;
    }
        /**
	 * @Id
	 * @Column(type="integer")
         * @GeneratedValue
	 */
	protected $id;


         /**
           * @Column(type="datetime")
	   */
        protected $date;

    	/**
	* @ManyToOne(targetEntity="Patient")
	* @JoinColumn(name="pid", referencedColumnName="pid")
	*/
	protected $patient;

        public function getPatient()
        {
            return $this->patient;
        }

        /**
	 * @Column(type="text")
	 */
        protected $title;

        public function setTitle($tit)
        {
            $this->title = $tit;
        }

        /**
	 * @Column(type="text")
	 */
        protected $diagnosis;

        public function setDiagnosis($val,$for)
        {
            $this->diagnosis=$for.":".$val;
        }

         /**
           * @Column(type="date")
	   */
        protected $begdate;

        function setBegDate($date)
        {
            $this->begdate=$date;
        }



         /**
           * @Column(type="date")
	   */
        protected $enddate;

        function setEndDate($date)
        {
            $this->enddate=$date;
        }

        public function getEndDate()
        {
            return $this->enddate;
        }

         /**
           * @Column(type="date")
	   */
        protected $returndate;

        function setReturnDate($date)
        {
            $this->returndate=$date;
        }

        /**
          * @Column(type="integer")
          */
        protected $occurrence;

        /**
          * @Column(type="integer")
          */
        protected $classification;

        /**
	 * @Column(type="text")
	 */
        protected $referredby;
        
        /**
	 * @Column(type="text")
	 */
        protected $extrainfo;

        /**
	 * @Column(type="integer")
	 */
        protected $activity;

        /**
	 * @Column(type="text")
	 */
        protected $comments;

        /**
	 * @Column(type="text")
	 */
        protected $user;
        
        /**
	 * @Column(type="text")
	 */
        protected $groupname;

        /**
	 * @Column(type="integer")
	 */
        protected $outcome;
        
        /**
	 * @Column(type="text")
	 */
        protected $destination;
}
?>
