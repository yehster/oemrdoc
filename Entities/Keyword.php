<?php
namespace library\doctrine\Entities;
/** @Entity 
 *	@Table(name="dct_keywords")
 */
class Keyword
{
	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	private $id;

	public function __construct($con)
	{
		$this->content=$con;
	}


	function getId()
	{
		return $this->id;
	}


	/** @Column(type="string",length=255) */
	private $content;

	function getContent()
	{
		return $this->content;
	}



}
?>
