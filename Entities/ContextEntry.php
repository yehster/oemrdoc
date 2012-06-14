<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');

/**
  * @Entity
  */
class ContextEntry extends DocumentEntry
{
    public function __construct($md,$pat,$auth)
    {
        parent::__construct($md,$pat,$auth);
    }
    //put your code here

    const classtype = "ContextEntry";
    public function getType()
    {
        return self::classtype;
    }

    
}

?>
