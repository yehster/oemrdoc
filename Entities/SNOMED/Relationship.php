<?php
namespace library\doctrine\Entities\SNOMED;

/** @Entity
 *  @Table(name="MRREL")
 */
class Relationship {
    /**
     * @Id
     * @Column(type="string")
     */
    protected $RUI;

    public function getRUI()
    {
        return $this->RUI;
    }

    /**
     * @Column(type="string")
     */
    protected $RELA;

    public function getRELA()
    {
        return $this->RELA;
    }

    /**
     *  @ManyToOne(targetEntity="Concept", inversedBy="relationships1")
     *  @JoinColumn(name="AUI1", referencedColumnName="AUI")
     */
    protected $concept1;
    public function getConcept1()
    {
        return $this->concept1;
    }

    /**
     *  @ManyToOne(targetEntity="Concept", inversedBy="relationships2")
     *  @JoinColumn(name="AUI2", referencedColumnName="AUI")
     */
    protected $concept2;

    public function getConcept2()
    {
        return $this->concept2;
    }

}
?>
