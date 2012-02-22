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
}

?>
