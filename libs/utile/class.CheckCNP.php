<?php
/* 
 * @modified - added support for Bucharest
 * 
 * @author Iosif Chiriluta 
 * @description This class checks if a CNP is valid or not. If the CNP is valid, this class pull out the information about the possessor of given CNP.
 * @release date 10/13/2011 
 * @last update 08/27/2012 
 * @version 2.0 
 * @link http://jzp.ro
 */ 
class CheckCNP{

	/**
	 * @access public 
	 * @var integer
	 */
	public $error;	

	/**
	 * @access protected
	 * @var string
	 */
	protected $cnp;

	/**
	 * @access protected
	 * @var string[]
	 */
	protected $cities = array(
		1 => "Alba",
		2 => "Arad",
		3 => "Arges",
		4 => "Bacau",
		5 => "Bihor",
		6 => "Bistrita-Nasaud",
		7 => "Botosani",
		8 => "Brasov",
		9 => "Braila",
		10 => "Buzau",
		11 => "Caras-Severin",
		12 => "Cluj",
		13 => "Constanta",
		14 => "Covasna",
		15 => "Dambovita",
		16 => "Dolj",
		17 => "Galati",
		18 => "Gorj",
		19 => "Harghita",
		20 => "Hunedoara",
		21 => "Ialomita",
		22 => "Iasi",
		23 => "Ilfov",
		24 => "Maramures",
		25 => "Mehedinti",
		26 => "Mures",
		27 => "Neamt",
		28 => "Olt",
		29 => "Prahova",
		30 => "Satu Mare",
		31 => "Salaj",
		32 => "Sibiu",
		33 => "Suceava",
		34 => "Teleorman",
		35 => "Timis",
		36 => "Tulcea",
		37 => "Vaslui",
		38 => "Valcea",
		39 => "Vrancea",
		40 => "Bucuresti",
		41 => "Bucuresti/Sectorul 1",
		42 => "Bucuresti/Sectorul 2",
		43 => "Bucuresti/Sectorul 3",
		44 => "Bucuresti/Sectorul 4",
		45 => "Bucuresti/Sectorul 5",
		46 => "Bucuresti/Sectorul 6",
		51 => "Calarasi",
		52 => "Giurgiu"
	);

	/**
	 * @access protected
	 * @var string[]
	 */
	protected $months = array(
		1 => "Ianuarie",
		2 => "Februarie",
		3 => "Martie",
		4 => "Aprilie",
		5 => "Mai",
		6 => "Iunie",
		7 => "Iulie",
		8 => "August",
		9 => "Septembrie",
		10 => "Octombrie",
		11 => "Noiembrie",
		12 => "Decembrie"
	);

	/**
	 * @access public
	 * @param string $cnp The CNP that will be validated
	 * @return void|boolean
	 */
	public function __construct($cnp) {
		
		$this->cnp = $cnp;

		if (FALSE === $this->_validate())
			return FALSE;
				
		$this->genre 	= (int)substr($this->cnp, 0, 1);
		$this->month 	= (int)substr($this->cnp, 3, 2);
		$this->day 		= (int)substr($this->cnp, 5, 2);
		$this->city 	= (int)substr($this->cnp, 7, 2);
		
		//if( (1 > $this->month || 12 < $this->month) || (1 > $this->day || 31 < $this->day) || (!isset($this->cities[$this->city])) ) {
		if( (1 > $this->month || 12 < $this->month) || (1 > $this->day || 31 < $this->day) ) {
			$this->error = 3;
			return FALSE;
		}
	}
	
	/**
	 * @access public
	 * @return string[] Returns all information extracted from CNP
	 */  
	public function fetchAllData() {
		return array(
			"genre" 	=> $this->getGenre(),
			"year" 		=> $this->getYear(),
			"month" 	=> $this->getMonth(),
			"day" 		=> $this->getDay(),
			"city" 		=> $this->getCity(),
			"resident" 	=> $this->getResidentInfo(),
			"stranger"	=> $this->getStrangerInfo()
		);
	}

	/**
	 * @access public
	 * @return boolean Checks and returns if the owner of CNP lives in Romania
	 */
	public function getResidentInfo() {
		return in_array($this->genre, array(7, 8)) ? TRUE : FALSE;
	}
	/**
	 * @access public
	 * @return boolean Checks and returns if the owner of CNP has Romanian nationality
	 */
	public function getStrangerInfo() {
		return in_array($this->genre, array(7, 8, 9)) ? TRUE : FALSE;
	}

 	/**
 	 * @access public
 	 * @return string[] Returns an array composed of number and initials genre
 	 */
	public function getGenre() {
		return array(
			$this->genre,
			in_array($this->genre, array(1, 3, 5, 7)) ? "m" : (
				in_array($this->genre, array(2, 4, 6, 8)) ? "f" : "n/a"
			)
		);
	}

	/**
	 * @access public
	 * @return string Returns the year extracted from CNP
	 */
	public function getYear() {
		return (
				in_array($this->genre, array(1, 2)) ? 19 : (
					in_array($this->genre, array(3, 4)) ? 18 : (
						in_array($this->genre, array(5, 6)) ? 20 : NULL
					) 
				)
			) . 
			substr($this->cnp, 1, 2);
	}

	/**
	 * @access public
	 * @return integer[] Returns an array composed of number and name of month extracted from CNP
	 */ 
	public function getMonth() {
		return array(
			$this->month,
			$this->months[$this->month]
		);
	}

	/**
	 * @access public
	 * @return mixed[] Returns an array composed of month day and week name of day extracted from CNP
	 */
	public function getDay() {
		return array(
			$this->day,
			date("l", strtotime("{$this->getYear()}/{$this->month}/{$this->day}"))
		);
	} 

	/**
	 * @access public
	 * @return int Returns an integer representing the age
	 */
	public function getAge(){
	
	    if($this->cnp[0]==1 || $this->cnp[0]==2 )
	        $date_birth = "19";
	    elseif($this->cnp[0]==3 || $this->cnp[0]==4 )
	        $date_birth = "18";
	    elseif($this->cnp[0]==5 || $this->cnp[0]==6 )
	        $date_birth = "20";
	    else
	        return false;
	    
		$date_birth .= $this->cnp[1].$this->cnp[2]."-".$this->cnp[3].$this->cnp[4]."-".$this->cnp[5].$this->cnp[6];
		
		return floor((time() - strtotime($date_birth)) / 31556926);
	}
	
	/**
	 * @access public
	 * @return mixed[] Returns an array composed of name of county and number extracted from CNP
	 */
	public function getCity() {
		return array (
			$this->city,
			$this->cities[$this->city]
		);
	}

	/**
	 * @access private
	 * @return boolean Checks if the CNP is valid
	 */
	private function _validate() {
		$key = "279146358279";
		
		if(13 !== strlen($this->cnp)) {
			$this->error = 1;
			return FALSE;
		}

		$s = 0;

		for($i = 0; $i <= 11; $i++)
			$s += $this->cnp[$i] * $key[$i];
		
		$s %= 11;

		if((10 === $s && "1" !== $this->cnp[12]) || (10 > $s && $s != $this->cnp[12])) {
			$this->error = 2;
			return FALSE;
		}

		return TRUE;
	}
}