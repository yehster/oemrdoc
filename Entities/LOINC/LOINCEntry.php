<?php
namespace library\doctrine\Entities\LOINC;
/** @Entity
 *  @Table(name="LOINC")
 */

class LOINCEntry {
    //put your code here

    /**
     * @Id
     * @Column(type="string")
     */
    protected $LOINC_NUM;
    
    public function getLOINC_NUM()
    {
        return $this->LOINC_NUM;
    }

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

    public function getLONG_COMMON_NAME()
    {
        return $this->LONG_COMMON_NAME;
    }
    /**
     * @Column(type="string")
     */
    protected $SHORTNAME;
    
}
?>
