<?php

namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_keyword_mappings")
 */

class ICD9KeywordMapping {
    public function __construct($kw,$code)
    {
        $this->keyword=$kw;
        $this->code=$cd;
    }    

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") 
     */
    protected $id;

	public function __construct($kw,$cd)
	{
		$this->keyword=$kw;
		$this->code=$cd;
	}


	function getId()
	{
		return $this->id;
	}    
    
}
?>
