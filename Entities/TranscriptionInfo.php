<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 
/**
  * @Entity
  */
class TranscriptionInfo extends DocumentEntry
{
    public function __construct($md,$pat,$auth,$lf)
    {
        parent::__construct($md,$pat,$auth);
        $this->transcription_file=$lf;
    }

        const classtype = "TranscriptionInfo";
        public function getType()
        {
            return self::classtype;
        }

    /**
    * @ManyToOne(targetEntity="library\doctrine\Entities\libre\libreFile", cascade={"persist"})
    * @JoinColumn(name="attr1", referencedColumnName="filename")
    */
    protected $transcription_file;
}

?>
