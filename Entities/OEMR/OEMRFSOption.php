<?php
namespace library\doctrine\Entities\OEMR;
/** @Entity
 *  @Table(name="fee_sheet_options")
 */
class OEMRFSOption{
    
    /**
     * @Id
     * @Column(type="text")
     * @GeneratedValue
     */    
    protected $fs_codes;

    public function getCode()
    {
        return $this->fs_codes;
    }
    /**
     * @Column(type="text")
     */
    protected $fs_category;
    
    public function getCategory()
    {
        return substr($this->fs_category,1);
    }
    /**
     * @Column(type="text")
     */
    protected $fs_option;
    
    public function getOption()
    {
        return substr($this->fs_option,1);
    }
}
?>
