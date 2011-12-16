<?php

namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="dct_med_names_usage")
 */
class MedNameUsage {
    
    public function __construct($medName)
    {
        $this->mn=$medName;
        $this->frequency=1;
    }
	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	protected $id;

    /**
     *  @OneToOne(targetEntity="MedName", inversedBy="usage")
     *  @JoinColumn(name="rxaui_id", referencedColumnName="RXAUI")
     */
    protected $mn;

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
