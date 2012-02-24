<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 
/**
  * @Entity
  */
class TranscriptionInfo extends DocumentEntry
{
    public function __construct($md,$pat,$auth)
    {
        parent::__construct($md,$pat,$auth);
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

    public function setTranscriptionFile($val)
    {
        $this->transcription_file=$val;
    }
}

?>
