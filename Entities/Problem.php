<?php
namespace library\doctrine\Entities;
include_once('DocumentEntry.php');
 /**
  * @Entity
  */
class Problem extends DocumentEntry
{

    public function __construct($md,$pat,$auth)
    {
        parent::__construct($md,$pat,$auth);        
    }

        const classtype = "Problem";
    public function getType()
    {
        return self::classtype;
    }
       	public function setCode($val,$for)
	{
            parent::setCode($val,$for);
	}


}
?>
