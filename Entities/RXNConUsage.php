<?php

namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="dct_med_names_usage")
 */
class RXNConUsage {
    
    public function __construct($rxnconso)
    {
        $this->RXNCon=$rxnconso;
        $this->frequency=1;
    }
	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	protected $id;

    /**
     *  @OneToOne(targetEntity="RXNConcept", inversedBy="usage")
     *  @JoinColumn(name="rxaui_id", referencedColumnName="RXAUI")
     */
    protected $RXNCon;

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
