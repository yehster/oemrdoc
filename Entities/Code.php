<?php
namespace library\doctrine\Entities;
/** @Entity 
 *	@Table(name="codes")
 */
class Code
{
	/**
	 * @Id 
	 * @Column(type="integer",name="id")
	 * @GeneratedValue(strategy="AUTO")
	*/
	private $id;



	function getId()
	{
		return $this->id;
	}


	/** @Column(type="integer") */
	private $code_type;
        
        public function getCode_type()
        {
            return $this->code_type;
        }

	/** @Column(type="string",length=255) */
	private $code_text;

	function getCodeText()
	{
		return $this->code_text;
	}


	/** @Column(type="string",length=255) */
	private $modifier;

	/** @Column(type="string",length=255) */
	private $code;

        function getCode()
        {
            return $this->code;
        }


        /** @Column(type="string")*/
        private $code_text_short;
}
?>
