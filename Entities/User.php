<?php
namespace library\doctrine\Entities;
/** @Entity
 *	@Table(name="users")
 */
class User
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	*/
	private $id;

	function getId()
	{
		return $this->id;
	}


	/** @Column(type="string") */
	protected $username;
        
	function getUsername()
	{
		return $this->username;
	}





}
?>

