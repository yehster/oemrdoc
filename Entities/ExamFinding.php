<?php
namespace library\doctrine\Entities;
include_once('ObservationMetadata.php');
 /**
  * @Entity
  */
class ExamFinding extends ObservationMetadata
{
    public function __construct($c,$ct,$txt)
    {
        parent:: __construct($c,$ct,$txt);
    }
}
?>
