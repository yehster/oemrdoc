<?php

class DocumentXMLHandler
{

    protected $em;
    protected $DOM;
    protected $mdDocumentType;

    protected $longDesc;
    protected $shortDesc;
    
    public function __construct($em,$DOM)
    {
        $this->em=$em;
        $this->DOM=$DOM;       
    }
    
    protected function determineDocType()
    {
        $docTags=$this->DOM->getElementsByTagName("document");
        if(count($docTags)==1)
        {
            $this->longDesc=$docTags->item(0)->getAttribute("type");
            $this->shortDesc=$docTags->item(0)->getAttribute("short_desc");
        }
    }
    
    protected function findOrCreateRoot()
    {
        $this->mdDocumentType = $this->em->getRepository('library\doctrine\Entities\DocumentType')->findOneBy(array('longDesc' => $this->longDesc));
        if($this->mdDocumentType==null)
        {
            $this->mdDocumentType=new library\doctrine\Entities\DocumentType($this->shortDesc,$this->longDesc);
        }
    }
    protected function findOrCreateSection($node,$parentMD)
    {
        $longDesc=$node->getAttribute("long_desc");
        $shortDesc=$node->getAttribute("short_desc");
        $qb = $this->em->createQueryBuilder()
            ->select("section")
            ->from("library\doctrine\Entities\SectionHeading","section")
            ->join("section.ci","ci")
            ->join("ci.parent","parent")
            ->where("section.shortDesc = :shortDesc AND section.longDesc=:longDesc")
            ->andWhere("parent=:parent");
        $qb->setParameter("shortDesc",$shortDesc);
        $qb->setParameter("longDesc",$longDesc);
        $qb->setParameter("parent",$parentMD);
        $qry=$qb->getQuery();
        $res=$qry->getResult();
        echo count($res);
        
    }
    protected function handleNode($node,$parentMD)
    {
        $metadata=null;
        $nodeType=$node->nodeName;
        switch($nodeType)
        {
            case "section":
                $metadata=$this->findOrCreateSection($node,$parentMD);
                break;
            case "narrative":
                break;
        }
        if($metadata!=null)
        {
            foreach($node->childNodes as $child)
            {
                $this->handleNode($child,$metadata);
            }
        }
    }
    public function parse()
    {
        $this->determineDocType();
        $this->findOrCreateRoot();
        $DOMRoot=$this->DOM->getElementsByTagName("root")->item(0);
        foreach($DOMRoot->childNodes as $child)
        {
                $this->handleNode($child,$this->mdDocumentType);
            
        }
    }
}
?>
