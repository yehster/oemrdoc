<?php

namespace library\doctrine\Entities;
include_once ('DocumentMetadata.php');

/** @Entity */
class TranscriptionInfoMetadata extends DocumentMetadata
{
        public function makeEntry($pat,$auth)
        {
            $retVal = new TranscriptionInfo($this,$pat,$auth);
            return $retVal;
        }

}

?>
