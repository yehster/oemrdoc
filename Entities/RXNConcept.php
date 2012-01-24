<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RXNConcept
 *
 * @author yehster
 */

namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="rxnconso")
 */
class RXNConcept {
    /**
     * @Id
     * @Column(type="string")
     */
    protected $RXAUI;

    public function getRXAUI()
    {
        return $this->RXAUI;
    }

    /**
    * @Column(type="string")
    */
    protected $RXCUI;

    public function getRXCUI()
    {
        return $this->RXCUI;
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
    protected $TTY;

    public function getTTY()
    {
        return $this->TTY;
    }

    /**
    * @Column(type="string")
    */
    protected $SAB;
}
?>
