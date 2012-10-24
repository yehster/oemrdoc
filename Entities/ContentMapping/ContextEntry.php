<?php
namespace library\doctrine\Entities\ContentMapping;


/**
  * @Entity
  * @Table(name="dct_context_entries")
  */
class ContextEntry implements \JsonSerializable
{

    use OrderedCodeEntry; 
    
    /**
     * @Id 
     * @Column(type="string") 
     */
    protected $uuid;

    /**
     * @ManyToOne(targetEntity="ContentGroup", inversedBy="context_entries", cascade={"persist","remove"})
     * @JoinColumn(name="content_group", referencedColumnName="uuid")
     */
    protected $content_group;    
    
   /**
    * @Column(type="string") 
    */
    protected $code;

   /**
    * @Column(type="string") 
    */
    protected $code_type;
    
   /**
    * @Column(type="string") 
    */
    protected $display_text;
    
    /**
     * @Column(type="integer") 
     */    
    protected $seq;
    
    public function jsonSerialize()
    {
        return OrderedCodeEntry::jsonSerialize();
    }
}
?>
