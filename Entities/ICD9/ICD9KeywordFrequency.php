<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_keyword_frequencies")
 */

class ICD9KeywordFrequency {

    public function __construct($kw,$freq=0)
    {
        $this->keyword=$kw;
        $this->frequency=$freq;
        
    }   
    
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    public function getID()
    {
        return $this->id;
    }

    /**
    * @OneToOne(targetEntity="ICD9Keyword")
    * @JoinColumn(name="keyword", referencedColumnName="id")
    */
    private $keyword;
    

    /**
     * @Column(type="integer")
     */
    private $frequency;

    public function increment()
    {
        $this->frequency++;
    }
}
?>
