<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RXNRelationship
 *
 * @author yehster
 */

namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="rxnrel")
 */
class RXNRelationship {

    /**
     * @Id
     * @Column(type="string")
     */
    protected $RUI;

    /**
    * @Column(type="string")
    */
    protected $REL;

    /**
    * @Column(type="string")
    */
    protected $RELA;

    /**
    * @Column(type="string")
    */
    protected $RXCUI1;

    /**
    * @Column(type="string")
    */
    protected $RXCUI2;

    /**
    * @Column(type="string")
    */
    protected $RXAUI1;

    /**
    * @Column(type="string")
    */
    protected $RXAUI2;

}
?>
