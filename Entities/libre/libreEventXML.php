<?php
namespace library\doctrine\Entities\libre;
include_once('libreEvent.php');

/**
  * @Entity
  */
class libreEventXML  extends libreEvent 
{
    public function getXMLDOM()
    {
        if(!$this->successful())
        {
            return null;
        }
        $fullpath=$this->message.$this->file->getFilename().".xml";
        echo $fullpath;
    }

    
}

?>
