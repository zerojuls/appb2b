<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		log_message('debug', "Model Class Initialized");
	}

	/**
	 * __get
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
        
        
        /**
        * CodeIgniter
        *
        * An open source application development framework for PHP 5.1.6 or newer
        *
        * Developert   :   
        * Create Date  :   
        * Description  :   Funciones para poblar un dropdown, grabar y listar
        * 
        * Company      :   
        */
        
        
        function get_dropdown($spName, $arrParam) {

            $arrCombo = $this->db->dropdown($spName, $arrParam);        
            return $arrCombo;
        }

        function set_grabar($spName, $arrParam) {

            $arrResultado = $this->db->query_sp($spName, $arrParam);
            return $arrResultado;
        }
        
        function get_listar($spName, $arrParam) {

            $arrResultado = $this->db->query_sp($spName, $arrParam);
            return $arrResultado;
        }
        
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */