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
            $this->em->persist($this->mdDocumentType);
        }
    }
    protected function findOrCreateSection($node,$parentMD)
    {
        $longDesc=$node->getAttribute("long_desc");
        $shortDesc=$node->getAttribute("short_desc");
        $code=$node->getAttribute("code");
        $code_type=$node->getAttribute("code_type");
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
        if(count($res)==0)
        {
            $retval = new library\doctrine\Entities\SectionHeading($shortDesc,$longDesc);
            $parentMD->addMetadata($retval);
            $this->em->persist($retval);
        }
        else
        {
            
            $retval=$res[0];
        }
        $retval->setCode($code,$code_type);
        return $retval;
    }
    
    protected function findOrCreateNarrative($node,$parentMD)
    {
        $longDesc=$parentMD->getlongDesc();
        $shortDesc=$parentMD->getshortDesc();
        $qb = $this->em->createQueryBuilder()
            ->select("nar")
            ->from("library\doctrine\Entities\NarrativeMetadata","nar")
            ->join("nar.ci","ci")
            ->join("ci.parent","parent")
            ->where("nar.shortDesc = :shortDesc AND nar.longDesc=:longDesc")
            ->andWhere("parent=:parent");
        $qb->setParameter("shortDesc",$shortDesc);
        $qb->setParameter("longDesc",$longDesc);
        $qb->setParameter("parent",$parentMD);
        $qry=$qb->getQuery();
        $res=$qry->getResult();
        if(count($res)==0)
        {
            $retval = new library\doctrine\Entities\NarrativeMetadata($shortDesc,$longDesc);
            $parentMD->addMetadata($retval);
            $this->em->persist($retval);
        }
        else
        {
            $retval=$res[0];
        }
        $retval->setCode($parentMD->getCode(),$parentMD->getCode_type());
        echo $retval->getlongDesc();
        return $retval;
    }
    
    protected function findOrCreateCheckbox($node,$parentMD)
    {
        echo $node->nodeName."\n";
    }
    protected function handleNode($node,$parentMD)
    {
        $metadata=null;
        $nodeType=$node->nodeName;
        switch($nodeType)
        {
            case "encounterContext":
                break;
            case "section":
                $metadata=$this->findOrCreateSection($node,$parentMD);
                break;
            case "narrative":
                $metadata=$this->findOrCreateNarrative($node,$parentMD);
                break;
            case "checkbox":
                $metadata=$this->findOrCreateCheckbox($node,$parentMD);
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
        $this->em->flush();
    }
}
?>
