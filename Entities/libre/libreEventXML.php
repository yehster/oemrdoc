<?php
namespace library\doctrine\Entities\libre;
include_once('libreEvent.php');

/**
  * @Entity
  */
class libreEventXML  extends libreEvent 
{
    const classtype = "XML";
    public function getType()
    {
        return self::classtype;
    }    
    
    public function getXMLDOM()
    {
        if(!$this->successful())
        {
            return null;
        }
        $fullpath=$this->message.$this->file->getFilename().".xml";
        $DOM = new \DOMDocument;
        $DOM->load($fullpath);
        return $DOM;
    }

    
}

?>
