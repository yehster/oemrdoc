<?php
namespace library\doctrine\Entities;
include_once ('OEMRListItem.php');

 /**
  * @Entity
  */
class OEMRProblem extends \library\doctrine\Entities\OEMRListItem
{
    function __construct($pat,$title="",$diagCode="",$diagType="ICD9")
    {
        parent::__construct($pat,$title);
        $this->diagnosis=$diagType.":".$diagCode;
    }


}
?>
