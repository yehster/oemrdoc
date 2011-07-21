<?php
namespace library\doctrine\Entities;
require("DocumentEntry.php");
  /**
  * @Entity
  */
 class Order extends DocumentEntry
 {
        const classtype = "Order";
        public function getType()
        {
            return self::classtype;
        }

 }
 ?>
