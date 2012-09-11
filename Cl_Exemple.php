<?php /**
 * 
 */
if(!isset($ROOTLEVEL)){	header("HTTP/1.0 404 Not Found");	die() ;		// on retourne une erreur 404 si l'on vient pas d'une page qui defini le root level}/* require_once( $ROOTLEVEL . "_MODELES/Cl_Account.php");*//**
 * @class Cl_Exemple
 * @brief Description de la classe très important !!!
 * @author Julien Coupez
 * @version 0.0.1
 *  
 */class Cl_Exemple{	 	// les membres privés n'apparaissent pas dans la doc, les comentaires servent donc juste au developpeur de la classe	private $_Id = 0; // int : Id de l'exemple	private $_Name; // type : variable qui fait...	 
    	/*membres public : ils necessites des commentaires avec des explications sur leur type, leur utlisation, etc */	 	/**	 * @var array $_ExempleList
	 * @brief Description de la variable _ExempleList	 * @see Test() : ligne pour faire un lien vers une fonction qui s'en sert
	 * @see Test2() : description du lien (pour decrire le context de l'utilisation)
	 */	public $_ExempleList;	/**	 * @var string $_OtherVar	 * @brief "Valeur 1" , "Valeur 2", "Valeur 3" (donne ce que peut contenir la variable, utile avec des enum en mysql)	 */	public $_OtherVar;	  	 	/**	 * Constructeur de classe	 * @param integer $theId : description de theId	 * @param string $theName : description de theName	 */	public function __construct($theId = 0, $theName = "")	{		$this->_Id = $theId;		$this->_Name = $theName;	  		$this->_ExempleList = array();		$this->_OtherVar = array();	  	}
	
		/**
	 * <b>Description de la Fonction Test</b>
	 *
	 * @return string : description de la valeur de retour
	 */
	public function Test()
	{
		return "Ca marche !";
	}

	
	
	
	/**
	 * <b>Description de la Fonction Test2</b>
	 * @see Test()
	 * @return string : description de la valeur de retour
	 */
	public function Test2()
	{
		return "Ca marche deux fois mieux !";
	}
	
		 	/**	 * Destructeur	 * @return void	 */	public function __destruct() {		 	}}?>
