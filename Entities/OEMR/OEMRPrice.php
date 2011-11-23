<?php
namespace library\doctrine\Entities\OEMR;
/** @Entity
 *  @Table(name="prices")
 */
class OEMRPrice{
    
    /**
     * @Id
     * @Column(type="text")
     */    
    protected $pr_id;

    /**
     * @Id
     * @Column(type="text")
     */    
    protected $pr_selector;
    
    /**
     * @Id
     * @Column(type="text")
     */    
    protected $pr_level;

    /**
     * @Id
     * @Column(type="float",name="pr_price")
     */       
    protected $price;
    
    public function getPrice()
    {
        return $this->price;
    }
}
?>
