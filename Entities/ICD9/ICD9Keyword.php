<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_keywords")
 */

class ICD9Keyword {
    public function __construct($text)
    {
        $this->text=$text;
    }    

    /**
     * @Id
     * @Column(type="string")
     */
    protected $text;
}
?>
