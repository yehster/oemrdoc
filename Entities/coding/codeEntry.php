<?php
namespace library\doctrine\Entities\coding;


/** @Entity 
 *  @Table(name="dct_coding_code_entries")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"ctxt"="contextOption"})
 */
class codeEntry {
    public function __construct($cd,$ct,$desc,$dt)
    {
	$this->uuid=uuid_create();
	$this->code=$cd;
	$this->code_type=$ct;
        $this->description=$desc;
        $this->display_text=$dt;
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
	 * @Column(type="string") 
 	 */
	protected $code;
	// the value of the code entry

        public function getCode()
        {
            return $this->code;
        }
	/**
	 * @Column(type="string") 
 	 */
	protected $code_type;
	// is this an icd-9 code, or a LOINC code etc.

        public function getCode_type()
        {
            return $this->code_type;
        }
    

	/**
	 * @Column(type="string") 
 	 */
	protected $description;

        public function getDescription()
        {
            return $this->description;
        }

        
	/**
	 * @Column(type="string") 
 	 */
	protected $display_text;
        
        public function getDisplay_text()
        {
            return $this->display_text;
        }
        
}

?>
