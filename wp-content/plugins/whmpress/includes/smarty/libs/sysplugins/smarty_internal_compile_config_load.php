<?php
/**
 * Smarty Internal Plugin Compile Config Load
 * Compiles the {config load} tag
 *
 * @package    Smarty
 * @subpackage Compiler
 * @author     Uwe Tews
 */

/**
 * Smarty Internal Plugin Compile Config Load Class
 *
 * @package    Smarty
 * @subpackage Compiler
 */
class Smarty_Internal_Compile_Config_Load extends Smarty_Internal_CompileBase {
	/**
	 * Attribute definition: Overwrites base class.
	 *
	 * @var array
	 * @see Smarty_Internal_CompileBase
	 */
	public $required_attributes = [ 'file' ];
	/**
	 * Attribute definition: Overwrites base class.
	 *
	 * @var array
	 * @see Smarty_Internal_CompileBase
	 */
	public $shorttag_order = [ 'file', 'section' ];
	/**
	 * Attribute definition: Overwrites base class.
	 *
	 * @var array
	 * @see Smarty_Internal_CompileBase
	 */
	public $optional_attributes = [ 'section', 'scope' ];
	
	/**
	 * Compiles code for the {config_load} tag
	 *
	 * @param  array $args array with attributes from parser
	 * @param  object $compiler compiler object
	 *
	 * @return string compiled code
	 */
	public function compile( $args, $compiler ) {
		static $_is_legal_scope = [ 'local' => true, 'parent' => true, 'root' => true, 'global' => true ];
		// check and get attributes
		$_attr = $this->getAttributes( $compiler, $args );
		
		if ( $_attr['nocache'] === true ) {
			$compiler->trigger_template_error( 'nocache option not allowed', $compiler->lex->taglineno );
		}
		
		// save possible attributes
		$conf_file = $_attr['file'];
		if ( isset( $_attr['section'] ) ) {
			$section = $_attr['section'];
		} else {
			$section = 'null';
		}
		$scope = 'local';
		// scope setup
		if ( isset( $_attr['scope'] ) ) {
			$_attr['scope'] = trim( $_attr['scope'], "'\"" );
			if ( isset( $_is_legal_scope[ $_attr['scope'] ] ) ) {
				$scope = $_attr['scope'];
			} else {
				$compiler->trigger_template_error( 'illegal value for "scope" attribute', $compiler->lex->taglineno );
			}
		}
		// create config object
		$_output = "<?php  Smarty_Internal_Extension_Config::configLoad(\$_smarty_tpl, $conf_file, $section, '$scope');?>";
		
		return $_output;
	}
}