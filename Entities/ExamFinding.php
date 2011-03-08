<?php
namespace library\doctrine\Entities;

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
