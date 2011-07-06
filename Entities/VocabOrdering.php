<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="dct_vocab_ordering")
 **/
class VocabOrdering {

    /**
     * @Id
     * @Column(type="string")
     */
    protected $classification;
    
    /**
     * @Column(type="integer")
     */
    protected $seq;
}


?>
