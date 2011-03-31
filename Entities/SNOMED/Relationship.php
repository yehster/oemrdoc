<?php
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

    /**
     *  @ManyToOne(targetEntity="Concept", inversedBy="relationships1"})
     *  @JoinColumn(name="AUI1", referencedColumnName="AUI")
     */
    protected $concept1;


    /**
     *  @ManyToOne(targetEntity="Concept", inversedBy="relationships2"})
     *  @JoinColumn(name="AUI2", referencedColumnName="AUI")
     */
    protected $concept2;

}
?>
