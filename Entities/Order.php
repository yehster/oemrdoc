<?php
namespace library\doctrine\Entities;
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
