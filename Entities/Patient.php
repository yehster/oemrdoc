<?php
namespace library\doctrine\Entities;
/** @Entity 
 *	@Table(name="patient_data")
 */
class Patient
{
	/**
	 * @Id 
	 * @Column(type="integer") 
	 * @GeneratedValue(strategy="AUTO")
	*/
	protected $pid;

        public function getPID()
        {
            return $this->pid;
        }
        
	// This is the ID column in the patient_data table which is autogenerated not truly the primary key
	/** 
	 * @Column(type="integer",name="id") 
	*/
	protected $id;

	/** @Column(type="string",length=255) */
	protected $fname;

	/**
         *  @Column(type="string") 
         */
	protected $pubpid;

        function getId()
	{
		return $this->id;
	}


	/** @Column(type="string",length=255) */
	protected $lname;

	/** @Column(type="string",length=255) */
	protected $mname;


	/** @Column(type="string",length=255) */
	protected $sex;

	/** @Column(type="date") */
	protected $DOB;

        
	/** @Column(type="string") */
        protected $pricelevel;
        
        public function getPricelevel()
        {
            return $this->pricelevel;
        }
        
	function getDOB()
	{
		return $this->DOB;
	}

	/** @Column(type="datetime", name="date") */
	protected $ts;


        public function displayName()
        {
            return $this->lname .',' . $this->fname . ' ' . $this->mname;
        }

        public function normName()
        {
            return $this->fname . ' ' . $this->lname;
            
        }
        public function displayNarrative()
        {
            $age = date_diff(new \DateTime,$this->DOB);
            $years=$age->format("%y");
            if(intval($years)>=3)
            {
                $ageStr= $years." year-old";
            }
            else
            {
                $months=intval($year)*12 +intval($age->format("%m"));
                $ageStr=$months." month-old";
            }
            return $this->fname . " " . $this->lname ." is a " . "$ageStr " . strtolower($this->sex);
        }
	function display()
	{


		echo $this->lname .',' . $this->fname . ' ' . $this->mname . '<br>';
		echo $this->pid . '<br>';
		echo $this->sex .'<br>';
		echo $this->id .'<br>';
		echo $this->DOB->format('m-d-y');
		echo '<br>';
		echo $this->ts->format('m-d-y h:i:s');
		echo '<br>';
		

	}
      /**
	* @OneToMany(targetEntity="OEMRInsuranceData", mappedBy="patient")
	* @OrderBy({"type" = "ASC"})
	*/
        protected $insurance_data;
        
        public function getInsurance_data()
        {
            return $this->insurance_data;
        }
}
?>
