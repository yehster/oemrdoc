<?php

/**
 * Class which defines RXNorm Names (for drug lookup).
 *
 * @author Kevin Yeh
 */

namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="rxnnames")
 */
class MedName {
    //put your code here


    /**
     * @Id
     * @Column(type="string")
     */
    protected $RXAUI;

    public function getRXAUI()
    {
        return $this->RXAUI;
    }

    /**
    * @Column(type="string")
    */
    protected $RXCUI;

    public function getRXCUI()
    {
        return $this->RXCUI;
    }
   /**
    * @Column(type="string") 
    */
    protected $str;

    public function getSTR()
    {
        return $this->str;
    }
   /**
    * @Column(type="string")
    */
    protected $TTY;

    public function getTTY()
    {
        return $this->TTY;
    }

    /**
     *  @OneToOne(targetEntity="MedNameUsage", mappedBy="mn", cascade={"persist"})
     */
    protected $usage;
    
    public function getUsage()
    {
        if($this->usage==null)
        {
            $this->usage=new MedNameUsage($this);
            $GLOBALS['em']->persist($this->usage);
        }
        return $this->usage;
    }
}
?>
