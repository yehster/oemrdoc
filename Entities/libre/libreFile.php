<?php

namespace library\doctrine\Entities\libre;
/** @Entity
 *  @Table(name="libre_file")
 */
class libreFile {
    
    public function __construct($fn)
    {
        $this->filename=$fn;
        $this->created=new \DateTime();
    }
     /**
     * @Id
     * @Column(type="text")
     */
    protected $filename;
    
    
    public function getFilename()
    {
        return $this->filename;
    }
    
    /*
     * @Column(type="text")
     */
    protected $original_path;
    
    /** 
    * @Column(type="datetime") 
    */
    protected $created;

    public function getCreated()
    {
        return $this->created;
    }
    
    /**
    * @OneToMany(targetEntity="libreEvent", mappedBy="file", cascade={"persist","remove"})
    * @OrderBy({"created" = "DESC"})
    */
    protected $events;
    
    public function addEvent($le)
    {
        if($this->events==null)
        {
            $this->events=new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->events->add($le);
    }
}

?>
