<?php
/** @Entity
 *  @Table(name="LOINC")
 *  @InheritanceType("SINGLE_TABLE")
 *  @DiscriminatorColumn(name="TTY", type="string")
 *  @DiscriminatorMap({"PT" = "PreferredTerm", "IS" = "Concept", "OP" = "Concept"})
 */

class LOINCEntry {
    //put your code here

    /**
     * @Id
     * @Column(type="string")
     */
    protected $LOINC_NUM;

    /**
     * @Column(type="string")
     */
    protected $COMPONENT;

    /**
     * @Column(type="string")
     */
    protected $PROPERTY;

    /**
     * @Column(type="string")
     */
    protected $TIME_ASPCT;

    /**
     * @Column(type="string")
     */
    protected $SYSTEM;

    /**
     * @Column(type="string")
     */
    protected $SCALE_TYP;

    /**
     * @Column(type="string")
     */
    protected $METHOD_TYP;

    /**
     * @Column(type="string")
     */
    protected $LONG_COMMON_NAME;

    /**
     * @Column(type="string")
     */
    protected $SHORTNAME;
    
}
?>
