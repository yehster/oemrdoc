<?php
namespace library\doctrine\Entities;
include_once('ObservationMetadata.php');
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
