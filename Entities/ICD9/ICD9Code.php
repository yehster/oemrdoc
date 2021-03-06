<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_codes")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"sect" = "ICD9Section", "NS"="ICD9NSCode", "SP"="ICD9SPCode"})
 */

class ICD9Code {
    public function __construct($cd,$sd,$par=null)
    {
        $this->code=$cd;
        $this->short_desc=$sd;
        $this->parent=$par;
        $this->frequency=0;
    }    
    /**
     * @Id
     * @Column(type="string")
     */
    protected $code;
    
    public function getCode()
    {
        return $this->code;
    }
    
    /**
      * @Column(type="string") 
      */
    protected $short_desc;
    
    public function getShort_desc()
    {
        return $this->short_desc;
    }
    /**
     * @ManyToOne(targetEntity="ICD9Code", inversedBy="children", cascade={"persist"})
     * @JoinColumn(name="parent", referencedColumnName="code")
     */    
    protected $parent;
 
    public function setParent($par)
    {
        $this->parent=$par;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
      /**
	* @OneToMany(targetEntity="ICD9Code", mappedBy="parent", cascade={"persist","remove"})
	* @OrderBy({"code" = "ASC"})
	*/
	protected $children;    

      /**
	* @OneToMany(targetEntity="ICD9Definition", mappedBy="icd9code", cascade={"persist","remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
	protected $definitions;    
        
        public function getDefinitions()
        {
            return $this->definitions;
        }

        /**
	* @OneToMany(targetEntity="ICD9KeywordMapping", mappedBy="code", cascade={"persist","remove"})
	*/
	protected $keywords;    
        
        protected $type="unknown";

        public function getType()
        {
            return $this->type;
        }
        
        /**
         * @Column(type="integer");
         */
        protected $frequency;
        
        public function getFrequency()
        {
            return $this->frequency;
        }
        
        public function incrementFreq()
        {
            $this->frequency++;
        }
}

?>
