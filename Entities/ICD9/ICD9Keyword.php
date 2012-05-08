<?php
namespace library\doctrine\Entities\ICD9;
/** @Entity
 *  @Table(name="dct_icd9_keywords")
 */

class ICD9Keyword {
    
    
    public static function normalize_text($text)
    {
        $text=preg_replace("/[^a-z0-9]/","",strtolower($text));
        return $text;
    }
    public function __construct($text)
    {
        $this->text=ICD9Keyword::normalize_text($text);
    }    

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    public function getID()
    {
        return $this->id;
    }
    /**
     * @Column(type="string")
     */    
    protected $text;
    
    public function getText()
    {
        return $this->text;
    }
}
?>
