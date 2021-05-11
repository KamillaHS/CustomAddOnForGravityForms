<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class Range_Slider_Field extends GF_Field {

	/**
	 * @var string $type The field type.
	 */
	public $type = 'range';

	/**
	 * Return the field title, for use in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'Pool Slider', 'rangeslider' );
	}

	/**
	 * Assign the field button to the Advanced Fields group.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * The settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	function get_form_editor_field_settings() {
		return array(
			'label_setting',
			'description_setting',
			'rules_setting',
			'default_value_setting',
			'range_setting',
			'slider_step',
			'slider_start',
			'slider_step_values',
			//'placeholder_setting',
			//'input_class_setting',
			//'css_class_setting',
			//'size_setting',
			//'admin_label_setting',
			//'default_value_setting',
			//'visibility_setting',
			'conditional_logic_field_setting',
		);
	}

	/**
	 * Enable this field for use with conditional logic.
	 *
	 * @return bool
	 */
	public function is_conditional_logic_supported() {
		return true;
	}

	/**
	 * The scripts to be included in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_inline_script_on_page_render() {

		// set the default field label for the range type field
		$script = sprintf( "function SetDefaultValues_range(field) {field.label = '%s';}", $this->get_form_editor_field_title() ) . PHP_EOL;

		// initialize the fields custom settings
		$script .= "jQuery(document).bind('gform_load_field_settings', function (event, field, form) {" .
		           "var inputClass = field.inputClass == undefined ? '' : field.inputClass;" .
		           "jQuery('#input_class_setting').val(inputClass);" .
		           "});" . PHP_EOL;

		// saving the range setting
		$script .= "function SetInputClassSetting(value) {SetFieldProperty('inputClass', value);}" . PHP_EOL;

		return $script;
	}

	/**
	 * Define the fields inner markup.
	 *
	 * @param array $form The Form Object currently being processed.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param null|array $entry Null or the Entry Object currently being edited.
	 *
	 * @return string
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		$id              = absint( $this->id );
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		// Prepare the value of the input ID attribute.
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$value = esc_attr( $value );

		// Get the value of the inputClass property for the current field.
		$inputClass = $this->inputClass;

		// Prepare the input classes.
		$size         = $this->size;
		$class_suffix = $is_entry_detail ? '_admin' : '';
		//$class        = $size . $class_suffix . ' ' . $inputClass;
		$class        = $this->type . ' ' .$field_id . $class_suffix;

		// Prepare the other input attributes.
		$tabindex              = $this->get_tabindex();
		$logic_event           = ! $is_form_editor && ! $is_entry_detail ? $this->get_conditional_logic_event( 'keyup' ) : '';
		$placeholder_attribute = $this->get_field_placeholder_attribute();
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute     = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$disabled_text         = $is_form_editor ? 'disabled="disabled"' : '';

		// Prepare the input tag for this field.
		//$input = "<input name='input_{$id}' id='{$field_id}' type='text' value='{$value}' class='{$class}' {$tabindex} {$logic_event} {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$disabled_text}/>";

		//return sprintf( "<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $input );
	





		if ( ! $is_entry_detail && ! $is_form_editor ) {

			if ( $this->has_calculation() ) {

				// calculation-enabled fields should be read only
				$read_only = 'readonly="readonly"';

			} else {

				$message          = $this->get_failed_validation_message();
				$validation_class = $this->failed_validation ? 'validation_message' : '';

				if ( ! $this->failed_validation && ! empty( $message ) && empty( $this->errorMessage ) ) {
					//$instruction = "<div class='instruction $validation_class'>" . $message . '</div>';
				}
			}
		} else if ( RG_CURRENT_VIEW == 'entry' ) {
			$value = GFCommon::format_number( $value, $this->numberFormat );
		}

		$step = ( isset( $this->slider_step ) && '' != $this->slider_step ) ? $this->slider_step : 1;
		$start = ( isset( $this->slider_start ) && '' != $this->slider_start ) ? $this->slider_start : 0;

		$html_input_type = ! $this->has_calculation() && ( $this->numberFormat != 'currency' && $this->numberFormat != 'decimal_comma' ) ? 'number' : 'text'; // chrome does not allow number fields to have commas, calculations and currency values display numbers with commas
		$step_attr       = "step='{$this->slider_step}'";
		$start_attr		 = "value='{$this->slider_start}'";

		$min = ( isset( $this->rangeMin ) && '' != $this->rangeMin ) ? $this->rangeMin : 0;
		$max = ( isset( $this->rangeMax ) && '' != $this->rangeMax ) ? $this->rangeMax : 10;

		$min_attr = "min='{$min}'";
		$max_attr = "max='{$max}'";

		$key1 = $this->slider_key1; $val1 = $this->slider_value1;
		$key2 = $this->slider_key2; $val2 = $this->slider_value2;
		$key3 = $this->slider_key3; $val3 = $this->slider_value3;
		$key4 = $this->slider_key4; $val4 = $this->slider_value4;
		$key5 = $this->slider_key5; $val5 = $this->slider_value5;
		$key6 = $this->slider_key6; $val6 = $this->slider_value6;
		$key7 = $this->slider_key7; $val7 = $this->slider_value7;
		$key8 = $this->slider_key8; $val8 = $this->slider_value8;
		$key9 = $this->slider_key9; $val9 = $this->slider_value9;
		$key10 = $this->slider_key10; $val10 = $this->slider_value10;

		//echo("2." . $key1 . ", ");
		//echo("2" . $val1 . ", ");
		//echo("2.5" . $this->slider_val1);


		if ( '' == $value ) {

			$value = ( $min + $max ) / 2;
		}

		$placeholder_attribute = $this->get_field_placeholder_attribute();

		$tabindex = $this->get_tabindex();

		$data_value_visibility = isset( $this->slider_value_visibility ) ? "data-value-visibility='{$this->slider_value_visibility}'" : "data-value-visibility='hidden'";

      $connects_attr = ( $this->slider_connect == "none" || $this->slider_connect == "" ) ? "data-connect=false" : "data-connect='{$this->slider_connect}'";

		if ( 'currency' == $this->numberFormat ) {
			// get current gravity forms currency
			$code = ! get_option( 'rg_gforms_currency' ) ? 'USD' : get_option( 'rg_gforms_currency' );
			if ( false === class_exists( 'RGCurrency' ) ) {
				require_once( GFCommon::get_base_path() . '/currency.php' );
			}
			$currency = new RGCurrency( GFCommon::get_currency() );
			$currency = $currency->get_currency( $code );

			// encode for html currency attribute
			$currency = "data-currency='" . json_encode($currency) . "'";
		} else {
			$currency = '';
		}


		return sprintf( "<div class='ginput_container ginput_container_range_slider'>" . 
							"<input name='input_%d' id='%s' type='range' {$step_attr} {$start_attr} {$min_attr} {$max_attr} {$data_value_visibility} ${connects_attr} value='%s' class='%s' data-min-relation='%s' data-max-relation='%s' data-value-format='%s' {$currency} {$tabindex} {$read_only} {$placeholder_attribute} %s/>" .
							"<div id='gsfslider_%d' class='slider-display'></div>%s" .
							"<div class='results_{$field_id}'></div>" .
							"<div id='range_maxValue_{$field_id}'>{$max}</div><div id='range_field_id'>{$field_id}</div>" .
							"<div id='range_key1_{$field_id}'>{$key1}</div><div id='range_val1_{$field_id}'>{$val1}</div>" .
							"<div id='range_key2_{$field_id}'>{$key2}</div><div id='range_val2_{$field_id}'>{$val2}</div>" .
							"<div id='range_key3_{$field_id}'>{$key3}</div><div id='range_val3_{$field_id}'>{$val3}</div>" .
							"<div id='range_key4_{$field_id}'>{$key4}</div><div id='range_val4_{$field_id}'>{$val4}</div>" .
							"<div id='range_key5_{$field_id}'>{$key5}</div><div id='range_val5_{$field_id}'>{$val5}</div>" .
							"<div id='range_key6_{$field_id}'>{$key6}</div><div id='range_val6_{$field_id}'>{$val6}</div>" .
							"<div id='range_key7_{$field_id}'>{$key7}</div><div id='range_val7_{$field_id}'>{$val7}</div>" .
							"<div id='range_key8_{$field_id}'>{$key8}</div><div id='range_val8_{$field_id}'>{$val8}</div>" .
							"<div id='range_key9_{$field_id}'>{$key9}</div><div id='range_val9_{$field_id}'>{$val9}</div>" .
							"<div id='range_key10_{$field_id}'>{$key10}</div><div id='range_val10_{$field_id}'>{$val10}</div>" .
							"<span id='text_{$field_id}'>Ingen størrelse valgt</span>" .
						"</div>", 
						$id, 
						$field_id, 
						esc_attr( $value ), 
						esc_attr( $class ),
						esc_attr( $this->slider_min_value_relation ), 
						esc_attr( $this->slider_max_value_relation ), 
						esc_attr( $this->numberFormat ), 
						$disabled_text, 
						$id, 
						$instruction );





	}





	  // # SUBMISSION -----------------------------------------------------------------------------------------------------

   /**
    * Whether this field expects an array during submission.
    * @return boolean
    */
	public function is_value_submission_array() {
		return false; // TODO: setup field type setting and toggle this on that condition
	 }
  
	 /**
	  * Validate the field value being submitted
	  * @param string|array $value The field value from get_value_submission().
	   * @param array        $form  The Form Object currently being processed.
	  */
	 public function validate( $value, $form ) {
  
		// the POST value has already been converted from currency or decimal_comma to decimal_dot and then cleaned in get_field_value()
  
		$value     = GFCommon::maybe_add_leading_zero( $value );
		$raw_value = $_POST[ 'input_' . $this->id ]; //Raw value will be tested against the is_numeric() function to make sure it is in the right format.
  
		$is_valid_number = $this->validate_range( $value ) && GFCommon::is_numeric( $raw_value, $this->numberFormat );
  
		if ( ! $is_valid_number ) {
		   $this->failed_validation  = true;
		   $this->validation_message = empty( $this->errorMessage ) ? $this->get_failed_validation_message() : $this->errorMessage;
		}
  
	 }
  
	 /**
	  * Retrieve the field value on submission.
	  * @param array     $field_values             The dynamic population parameter names with their corresponding values to be populated.
	  * @param bool|true $get_from_post_global_var Whether to get the value from the $_POST array as opposed to $field_values.
	  *
	  * @return array|string
	  */
	  public function get_value_submission( $field_values, $get_from_post_global_var = true ) {
  
		  $value = $this->get_input_value_submission( 'input_' . $this->id, $this->inputName, $field_values, $get_from_post_global_var );
		  $value = trim( $value );
		  if ( $this->numberFormat == 'currency' ) {
			  require_once( GFCommon::get_base_path() . '/currency.php' );
			  $currency = new RGCurrency( GFCommon::get_currency() );
			  $value    = $currency->to_number( $value );
		  } else if ( $this->numberFormat == 'decimal_comma' ) {
			  $value = GFCommon::clean_number( $value, 'decimal_comma' );
		  } else if ( $this->numberFormat == 'decimal_dot' ) {
			  $value = GFCommon::clean_number( $value, 'decimal_dot' );
		  }
		  
  		 
		  return $value;
	  }
  
	  /**
	   * Validates the range of the number according to the field settings.
	   *
	   * @param array $value A decimal_dot formatted string
	   * @return true|false True on valid or false on invalid
	   */
	  private function validate_range( $value ) {
  
		  if ( ! GFCommon::is_numeric( $value, 'decimal_dot' ) ) {
			  return false;
		  }
  
		  if ( ( is_numeric( $this->rangeMin ) && $value < $this->rangeMin ) ||
			  ( is_numeric( $this->rangeMax ) && $value > $this->rangeMax )
		  ) {
			  return false;
		  } else {
			  return true;
		  }
	  }
  
	  public function get_failed_validation_message() {
		  $min     = $this->rangeMin;
		  $max     = $this->rangeMax;
		  $message = '';
  
		  if ( is_numeric( $min ) && is_numeric( $max ) ) {
			  $message = sprintf( __( 'Please enter a value between %s and %s.', 'gravityforms' ), "<strong>$min</strong>", "<strong>$max</strong>" );
		  } else if ( is_numeric( $min ) ) {
			  $message = sprintf( __( 'Please enter a value greater than or equal to %s.', 'gravityforms' ), "<strong>$min</strong>" );
		  } else if ( is_numeric( $max ) ) {
			  $message = sprintf( __( 'Please enter a value less than or equal to %s.', 'gravityforms' ), "<strong>$max</strong>" );
		  } else if ( $this->failed_validation ) {
			  $message = __( 'Please enter a valid number', 'gravityforms' );
		  }
  
		  return $message;
	  }
  
  
	  // # ENTRY RELATED --------------------------------------------------------------------------------------------------
  
	 /**
	  * Sanitize and format the value before it is saved to the Entry Object.
	  * @param string $value      The value to be saved.
	   * @param array  $form       The Form Object currently being processed.
	   * @param string $input_name The input name used when accessing the $_POST.
	   * @param int    $lead_id    The ID of the Entry currently being processed.
	   * @param array  $lead       The Entry Object currently being processed.
	  * @return array|string The safe value.
	  */
	 public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {
  
		$value = GFCommon::maybe_add_leading_zero( $value );
  
		$lead  = empty( $lead ) ? RGFormsModel::get_lead( $lead_id ) : $lead;
		$value = $this->has_calculation() ? GFCommon::round_number( GFCommon::calculate( $this, $form, $lead ), $this->calculationRounding ) : GFCommon::clean_number( $value, $this->numberFormat );
		//return the value as a string when it is zero and a calc so that the "==" comparison done when checking if the field has changed isn't treated as false
		if ( $this->has_calculation() && $value == 0 ) {
		   $value = '0';
		}
  
		return $value;
	 }
  
	 /**
	  * Format the entry value for when the field/input merge tag is processed. Not called for the {all_fields} merge tag.
	  *
	  * Return a value that is safe for the context specified by $format.
	  *
	  * @since  Unknown
	  * @access public
	  *
	  * @param string|array $value      The field value. Depending on the location the merge tag is being used the following functions may have already been applied to the value: esc_html, nl2br, and urlencode.
	  * @param string       $input_id   The field or input ID from the merge tag currently being processed.
	  * @param array        $entry      The Entry Object currently being processed.
	  * @param array        $form       The Form Object currently being processed.
	  * @param string       $modifier   The merge tag modifier. e.g. value
	  * @param string|array $raw_value  The raw field value from before any formatting was applied to $value.
	  * @param bool         $url_encode Indicates if the urlencode function may have been applied to the $value.
	  * @param bool         $esc_html   Indicates if the esc_html function may have been applied to the $value.
	  * @param string       $format     The format requested for the location the merge is being used. Possible values: html, text or url.
	  * @param bool         $nl2br      Indicates if the nl2br function may have been applied to the $value.
	  *
	  * @return string
	  */
	 public function get_value_merge_tag( $value, $input_id, $entry, $form, $modifier, $raw_value, $url_encode, $esc_html, $format, $nl2br ) {
  
		return GFCommon::format_number( $value, $this->numberFormat );
  
	 }
  
	 /**
	  * Format the entry value for display on the entries list page.
	  *
	  * Return a value that's safe to display on the page.
	  *
	  * @param string|array $value    The field value.
	  * @param array        $entry    The Entry Object currently being processed.
	  * @param string       $field_id The field or input ID currently being processed.
	  * @param array        $columns  The properties for the columns being displayed on the entry list page.
	  * @param array        $form     The Form Object currently being processed.
	  *
	  * @return string
	  */
	  public function get_value_entry_list( $value, $entry, $field_id, $columns, $form ) {
  
		  return GFCommon::format_number( $value, $this->numberFormat );
  
	  }
  
	 /**
	   * Format the entry value for display on the entry detail page and for the {all_fields} merge tag.
	   *
	   * Return a value that's safe to display for the context of the given $format.
	   *
	   * @param string|array $value    The field value.
	   * @param string       $currency The entry currency code.
	   * @param bool|false   $use_text When processing choice based fields should the choice text be returned instead of the value.
	   * @param string       $format   The format requested for the location the merge is being used. Possible values: html, text or url.
	   * @param string       $media    The location where the value will be displayed. Possible values: screen or email.
	   *
	   * @return string
	   */
	  public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
  
		  return GFCommon::format_number( $value, $this->numberFormat );
  
	  }

	  




}

GF_Fields::register( new Range_Slider_Field() );
