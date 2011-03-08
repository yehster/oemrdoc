<?php
namespace library\doctrine\Entities;

/** @Entity
 *  @Table(name="dct_option_sets")
 */
class OptionSet
{
    public function __construct()
    {
	$this->uuid=uuid_create();
	$this->created = new \DateTime();
	$this->modified = new \DateTime();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @Id
     * @Column(type="string")
     */
    protected $uuid;
}
?>
