<?php
namespace library\doctrine\Entities;
/** @Entity 
 *	@Table(name="dct_keyword_code_map")
 */
class KeywordCodeAssociation
{
	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	private $id;

	public function __construct($kw,$cd)
	{
		$this->keyword=$kw;
		$this->code=$cd;
	}


	function getId()
	{
		return $this->id;
	}


	/**
	* @ManyToOne(targetEntity="Keyword")
	* @JoinColumn(name="keyword_id", referencedColumnName="id")
	*/
	private $keyword;
        
	public function getKeyword()
	{
		return $this->keyword;
	}

	/**
	* @ManyToOne(targetEntity="Code")
	* @JoinColumn(name="code_id", referencedColumnName="id")
	*/
	private $code;

	public function getCode()
	{
		return $this->code;
	}

}
?>
