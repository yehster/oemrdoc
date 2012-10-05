<?php

/**
 * Description of DOMManager
 *
 * @author yehster
 */
class DOMManager {
    //put your code here
    public function __construct($rootTag,DOMDocument $DOM=null)
    {
        if(empty($DOM))
        {
            $this->DOM = new DOMDocument("1.0","utf-8");
        }
        
        $this->root=$this->DOM->createElement($rootTag);
        $this->DOM->appendChild($this->root);
        
    }
    protected $root;
    
    public function getRoot()
    {
        return $this->root;
    }
    protected $DOM;

    public function addScript(\DOMElement $parent,$scriptName)
    {
        $retval =$this->DOM->createElement("script");
        $retval->setAttribute("src",$scriptName);
        $parent->appendChild($retval);
        return $retval;
    }

    public function addStyle(\DOMElement $parent,$sheet)
    {


        $retval=$this->DOM->createElement("style","@import url('".$sheet."');");
        $retval->setAttribute("type","text/css");
        $retval->setAttribute("media","all");
        $parent->appendChild($retval);
        return $retval;
    }

    public function addElement(\DOMElement $parent,$tag,$content=null,array $attributes=null)
    {
        if($content!==null)
        {
            $retval=$this->DOM->createElement($tag,$content);        
        }
        else
        {
            $retval=$this->DOM->createElement($tag);
        }
        $parent->appendChild($retval);
        if(!empty($attributes))
        {
            foreach($attributes as $key=>$value)
            {
                $retval->setAttribute($key,$value);
            }
        }
        return $retval;
    }    
    
    public function addRadio(\DOMElement $parent, $choices, $attributes=null, $default=-1, $labelAttributes)
    {
        $retval=array();
        for($choiceIdx=0;$choiceIdx<count($choices);$choiceIdx++)
        {
            if($labelAttributes!=null)
            {
                $label=$this->addElement($parent,"span",$choices[$choiceIdx],$labelAttributes);
                $retval[$choiceIdx]=$this->addElement($label,"input",null,$attributes);            
            }
            else
            {
                $retval[$choiceIdx]=$this->addElement($parent,"input",null,$attributes);            
            }
            $retval[$choiceIdx]->setAttribute("value",$choices[$choiceIdx]);
            $retval[$choiceIdx]->setAttribute("type","radio");
            if($default==$choiceIdx)
            {
                $retval[$choiceIdx]->setAttribute("checked","true");
            }
        }
    }
    
    public function addSelect(\DOMElement $parent, $choices, $attributes=null, $default=-1)
    {
        $retval=$this->addElement($parent,"SELECT",null,$attributes);
        for($choiceIdx=0;$choiceIdx<count($choices);$choiceIdx++)
        {
            $option =$this->addElement($retval,"OPTION",$choices[$choiceIdx]);
            if($default==$choiceIdx)
            {
                $option->setAttribute("selected","true");
            }
        }

        return $retval;
    }
    public function saveHTML(\DOMElement $element=NULL)
    {
        if($element=null)
        {
            $element=$this->root;
        }
        return $this->DOM->saveHTML($element);
    }
            
}

?>
