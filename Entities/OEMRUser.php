<?php
namespace library\doctrine\Entities;
/** @Entity
 *  @Table(name="users")
*/
class OEMRUser {
        /**
	 * @Id
	 * @Column(type="integer")
         * @GeneratedValue
	 */
	protected $id;


        
        public function getID()
        {
            return $this-id;
        }

        /**
         * @Column(type="string")
         */
        protected $username;

        public function getUsername()
        {
            return $this->username;
        }
        
        /**
         * @Column(type="string")
         */
        protected $fname;

        public function getFname()
        {
            return $this->fname;
        }
        
        /**
         * @Column(type="string")
         */
        protected $mname;
        public function getMname()
        {
            return $this->mname;
        }
        
        /**
         * @Column(type="string")
         */
        protected $lname;
        public function getLname()
        {
            return $this->lname;
        }
        
        
        /**
         * @Column(type="string")
         */        
        protected $password;

        public function passwordHashMatches($hash)
        {
            return ($hash==$this->password);
        }
}

?>
