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
    }    
    /**
     * @Id
     * @Column(type="string")
     */
    protected $code;
    
    /**
      * @Column(type="string") 
      */
    protected $short_desc;
    
    /**
     * @ManyToOne(targetEntity="ICD9Code", inversedBy="children", cascade={"persist"})
     * @JoinColumn(name="parent", referencedColumnName="code")
     */    
    protected $parent;
 
    public function setParent($par)
    {
        $this->parent=$par;
    }
    
      /**
	* @OneToMany(targetEntity="ICD9Code", mappedBy="parent", cascade={"persist","remove"})
	* @OrderBy({"seq" = "ASC"})
	*/
	private $children;    
}

?>
