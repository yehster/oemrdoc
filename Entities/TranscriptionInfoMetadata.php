<?php

namespace library\doctrine\Entities;
include_once ('DocumentMetadata.php');

/** @Entity */
class TranscriptionInfoMetadata extends DocumentMetadata
{
        public function makeEntry(Patient $pat,$auth)
        {
            $retVal = new TranscriptionInfo($this,$pat,$auth);
            return $retVal;
        }

}

?>
