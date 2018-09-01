<?php defined('ABSPATH') or die;

/**
 * @package    pixtypes
 * @category   core
 * @author     Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade Media
 */
class PixtypesValidatorImpl implements PixtypesValidator {

	/** @var PixtypesMeta plugin configuration */
	protected $meta = null;

	/** @var PixtypesMeta field information */
	protected $fields = null;

	/**
	 * @param array config
	 */
	static function instance($config = null, $fields = null) {
		$i = new self;
		$i->configure($config, $fields);
		return $i;
	}

	/**
	 * Apply configuration.
	 *
	 * Fields array is assumed to be flat. The class will not perform any field
	 * extraction itself.
	 */
	protected function configure($config = null, $fields = null) {
		$config !== null or $config = array();
		$fields !== null or $fields = array();

		if (is_array($config)) {
			$this->meta = pixtypes::instance('PixtypesMeta', $config);
		}
		else { // non-array; assume meta object
			$this->meta = $config;
		}

		if (is_array($fields)) {
			$this->fields = pixtypes::instance('PixtypesMeta', $fields);
		}
		else { // non-array; assume meta object
			$this->fields = $fields;
		}
	}

	/**
	 * Validation will only be performed on input keys not on all field keys to
	 * allow for partial input validation.
	 *
	 * @param array input
	 * @return array errors (empty if no errors)
	 */
	function validate($input) {
		$errors = array();
		$defaults = pixtypes::defaults();
		$plugin_checks = $this->meta->get('checks', array());

		foreach ($input as $key => $value) {

			$field = $this->fields->get($key);

			// Calculate validation rules
			// --------------------------

			$rules = array();
			// check pixtypes defaults
			if (isset($defaults['checks'][$field['type']])) {
				$rules = $defaults['checks'][$field['type']];
			}
			// check theme defaults
			if (isset($plugin_checks[$field['type']])) {
				$rules = array_merge($rules, $plugin_checks[$field['type']]);
			}
			// check field presets
			if (isset($field['checks'])) {
				$rules = array_merge($rules, $field['checks']);
			}

			// Perform validation
			// ------------------

			foreach ($rules as $rule) {
				$callback = pixtypes::callback($rule, $this->meta);
				$valid = call_user_func($callback, $input[$key], $field, $this);
				if ( ! $valid) {
					isset($errors[$key]) or $errors[$key] = array();
					$errors[$key][$rule] = $this->error_message($rule);
				}
			}
		}

		return $errors;
	}

	/** @var array error messages */
	protected static $error_message_cache = null;

	/**
	 * @param string rule
	 * @return string error message
	 */
	function error_message($rule) {
		if (self::$error_message_cache === null) {
			$defaults = pixtypes::defaults();
			$default_errors = $defaults['errors'];
			$plugin_errors = $this->meta->get('errors', array());
			self::$error_message_cache = array_merge($default_errors, $plugin_errors);
		}

		return self::$error_message_cache[$rule];
	}

} # class