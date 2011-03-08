<?php
namespace library\doctrine\Entities;
/** @Entity 
 *	@Table(name="observation_identifiers")
 */
 class ObservationIdentifier
 {
	/**
	 * @Id 
	 * @Column(type="string") 
	 */
	private $uuid;
	
	public function __construct()
	{
		$this->uuid=uuid_create();
    	}

	/**
	 * @Column(type="string")
	 */
	private $shortname;
	
	/**
	 * @Column(type="string")
	 */
	private $longname;
	
	/**
	 * @Column(type="string")
	 */
	private $displayname;


	/**
	 * @Column(type="string")
	 */
	private $component;
	// what component is being observed (LOINC part 1)

	/**
	 * @Column(type="string")
	 */
	 private $property;
	//What property is being observed (e.g. mass/concentration/finding) (LOINC part 2)	

	/**
	 * @Column(type="string")
	 */
	 private $time_aspect;
	//What time period or point is this observation being made (LOINC part 3)	
	
	
	/**
	 * @Column(type="string")
	 */
	private $system;
	//Which system is being observed (e.g. resp, CV) (LOINC part 4)
	
	/**
	 * @Column(type="string")
	 */
	private $scale_type;
	//Which scale of observation (e.g. quantitive, ordinal, narrative)  (LOINC part 5)
	

	/**
	 * @Column(type="string")
	 */
	private $method;
	//What method was used for the observation (e.g. observed , (LOINC part 6)

	/**
	 * @Column(type="string")
	 */
	private $loinc_num;
	// The loinc number if it exists
	
	 
 }
 ?>
