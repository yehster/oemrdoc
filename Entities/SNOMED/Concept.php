<?php
namespace library\doctrine\Entities\SNOMED;
/** @Entity
 *  @Table(name="MRCONSO")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="TTY", type="string")
 *  @DiscriminatorMap({"PT" = "PreferredTerm"})
 */

class Concept {

    /**
     * @Id
     * @Column(type="string")
     */
    protected $AUI;

    public function getAUI()
    {
        return $this->AUI;
    }

   /**
    * @Column(type="string")
    */
    protected $str;

    public function getSTR()
    {
        return $this->str;
    }

    /**
     * @Column(type="string")
     */
    protected $tty;

    public function getTTY()
    {
        return $this->tty;
    }

    /**
     * @Column(type="string")
     */
    protected $SAB;

    public function getSAB()
    {
        return $this->SAB;
    }

}
?>
