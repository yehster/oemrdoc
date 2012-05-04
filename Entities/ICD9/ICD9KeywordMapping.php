<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_keyword_mappings")
 */

class ICD9KeywordMapping {
    public function __construct($kw,$code)
    {
        $this->keyword=$kw;
        $this->code=$code;
    }    

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    function getId()
    {
            return $this->id;
    }    


    /**
    * @ManyToOne(targetEntity="ICD9Keyword")
    * @JoinColumn(name="keyword", referencedColumnName="text")
    */
    private $keyword;
    
    
    /**
    * @ManyToOne(targetEntity="ICD9Code")
    * @JoinColumn(name="code", referencedColumnName="code")
    */
    private $code;    
    
}
?>
