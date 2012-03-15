<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_definitions")
 */

class ICD9Definition {
    //put your code here

    public function __construct($cd,$sq,$def,$dct_code)
    {
        $this->code=$cd;
        $this->seq=$sq;
        $this->definition=$def;
        $this->icd9code=$dct_code;
        error_log("---->>>>".$this->code);
    }    
    
    /** @Id @Column(type="string") */
    protected $code;  
    
    /** @Id @Column(type="integer") */    
    protected $seq;
    
    /** @Column(type="string") */    
    protected $definition;
    
    /**
     * @ManyToOne(targetEntity="ICD9Code", inversedBy="definitions", cascade={"persist"})
     * @JoinColumn(name="code", referencedColumnName="code")
     */
    protected $icd9code;    
}

?>
