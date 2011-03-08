<?php
namespace library\doctrine\Entities;

 /**
  * @Entity
  */
class Symptom extends ObservationMetadata
{
    public function __construct($c,$ct,$txt)
    {
        parent:: __construct($c,$ct,$txt);
    }
}
?>
