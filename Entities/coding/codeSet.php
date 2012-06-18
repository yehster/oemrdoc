<?php
namespace library\doctrine\Entities\coding;

/** @Entity 
 *  @Table(name="dct_coding_code_sets")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="discr", type="string")
 *  @DiscriminatorMap({"ctxt"="contextList"})
 */
class codeSet {

    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;    
}

?>
