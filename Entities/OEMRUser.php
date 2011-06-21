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

        /**
         * @Column(type="string")
         */
        protected $username;

        
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
