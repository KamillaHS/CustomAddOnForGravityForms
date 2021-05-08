<?php

GFForms::include_addon_framework();

class GFRangeSlider extends GFAddOn {

	protected $_version = GF_RANGE_SLIDER_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'rangeslider';
	protected $_path = 'rangeslider/rangeslider.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Range Slider - A Gravity Forms Add-On';
	protected $_short_title = 'Range Slider';

	/**
	 * @var object $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @return object $_instance An instance of this class.
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Include the field early so it is available when entry exports are being performed.
	 */
	public function pre_init() {
		parent::pre_init();

		if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' ) ) {
			require_once( 'includes/class-range-slider-field.php' );
		}
	}

	public function init_admin() {
		parent::init_admin();

		add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );
		add_action( 'gform_field_appearance_settings', array( $this, 'field_appearance_settings' ), 10, 2 );
	}

	public function init() {
		parent::init();

		// creating the slider field
		add_action( 'gform_editor_js_set_default_values', array( $this, 'set_defaults' ) );
		add_action( 'gform_editor_js', array( $this, 'editor_js' ) );
		add_filter( 'gform_field_standard_settings' , array( $this, 'slider_settings' ) , 10, 2 );
		
		// control submission & rendering of form_settings
        add_filter( 'gform_pre_submission_filter', array( $this, 'pre_submission_filter' ) );
        ( isset( $_GET['page'] ) && 'gf_entries' == $_GET['page'] ) ? add_filter( 'gform_admin_pre_render', array( $this, 'pre_submission_filter' ) ) : FALSE;

	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Include my_script.js when the form contains a 'range' type field.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'my_script_js',
				'src'     => $this->get_base_url() . '/js/my_script.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( 'field_types' => array( 'range' ) ),
				),
			),

		);
		echo "<script>console.log($scripts);</script>";
		
		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Include my_styles.css when the form contains a 'range' type field.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'my_styles_css',
				'src'     => $this->get_base_url() . '/css/my_styles.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'field_types' => array( 'range' ) )
				)
			)
		);

		return array_merge( parent::styles(), $styles );
	}


	// # FIELD SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Add the tooltips for the field.
	 *
	 * @param array $tooltips An associative array of tooltips where the key is the tooltip name and the value is the tooltip.
	 *
	 * @return array
	 */
	public function tooltips( $tooltips ) {
		$rangeslider_tooltips = array(
			'input_class_setting' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Input CSS Classes', 'rangeslider' ), esc_html__( 'The CSS Class names to be added to the field input.', 'rangeslider' ) ),
			'number_range' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Minimum', 'rangeslider' ), esc_html__( 'Enter the minimum and maximum values of the slider. If the minimum is set to 0, the default start value of "No size selected" will be shown. If set to 1, the default value will not get shown. If you do not know what you are doing, do not change this value. The maximum value should be the same number of step values you have entered.', 'rangeslider' ) ),
			'slider_start' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Start', 'rangeslider' ), esc_html__( 'Enter the start value of the slider. As default, this is set to 0, so that the default value gets shown. This value should be the same as the minimum value. Do not set this to any other value than the minimum value, if you do not know what you are doing.', 'rangeslider' ) ),
			'slider_step' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Step', 'rangeslider' ), esc_html__( 'Enter how far each step between minimum and max should be. As default, this is 1. If you do not know what you are doing, do not change this value.', 'rangeslider' ) ),
			'slider_step_values' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Slider Step Values', 'rangeslider' ), esc_html__( 'Enter a key and value for each step of the slider. The key is what the user is gonna see.  The value is what is gonna be used for the calculation.', 'rangeslider' ) ),
			
		);

		return array_merge( $tooltips, $rangeslider_tooltips );
	}

	/**
	 * Add the custom setting for the RangeSlider field to the Appearance tab.
	 *
	 * @param int $position The position the settings should be located at.
	 * @param int $form_id The ID of the form currently being edited.
	 */
	 /*
	public function field_appearance_settings( $position, $form_id ) {
		// Add our custom setting just before the 'Custom CSS Class' setting.
		if ( $position == 250 ) {
			?>
			<li class="input_class_setting field_setting">
				<label for="input_class_setting">
					<?php esc_html_e( 'Input CSS Classes', 'rangeslider' ); ?>
					<?php gform_tooltip( 'input_class_setting' ) ?>
				</label>
				<input id="input_class_setting" type="text" class="fieldwidth-1" onkeyup="SetInputClassSetting(jQuery(this).val());" onchange="SetInputClassSetting(jQuery(this).val());"/>
			</li>

			<?php
		}
	}
	*/


	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

    /**
	 * Set default values when adding a slider
	 *
	 * @since    0.1
	 */
	 function set_defaults() {
    	?>
    	    case "range" :
    	    	field.label = "Untitled";
    	        field.rangeMin = 0;
    	        field.rangeMax = 10;
    	        field.slider_step = 1;
				field.slider_start = 0;
    	        field.slider_value_visibility = "hidden";
    	    break;
    	<?php
    } // end set_defaults

    /**
	 * Execute javascript for proper loading of field
	 *
	 * @since    0.1
	 */
    function editor_js() {
    	?>
    		<script type='text/javascript'>
    			jQuery(document).ready(function($) {

    				// Bind to the load field settings event to initialize the slider settings
    				$(document).bind("gform_load_field_settings", function(event, field, form){
    					jQuery("#slider_min_value_relation").val(field['slider_min_value_relation']);
    					jQuery("#slider_max_value_relation").val(field['slider_max_value_relation']);
    					jQuery("#slider_step").val(field['slider_step']);
						jQuery("#slider_start").val(field['slider_start']);
						jQuery("#slider_value_visibility").val(field['slider_value_visibility']);
						
						jQuery("#slider_key1").val(field['slider_key1']); 
						jQuery("#slider_value1").val(field['slider_value1']);
						jQuery("#slider_key2").val(field['slider_key2']); jQuery("#slider_value2").val(field['slider_value2']);
						jQuery("#slider_key3").val(field['slider_key3']); jQuery("#slider_value3").val(field['slider_value3']);
						jQuery("#slider_key4").val(field['slider_key4']); jQuery("#slider_value4").val(field['slider_value4']);
						jQuery("#slider_key5").val(field['slider_key5']); jQuery("#slider_value5").val(field['slider_value5']);
						jQuery("#slider_key6").val(field['slider_key6']); jQuery("#slider_value6").val(field['slider_value6']);
						jQuery("#slider_key7").val(field['slider_key7']); jQuery("#slider_value7").val(field['slider_value7']);
						jQuery("#slider_key8").val(field['slider_key8']); jQuery("#slider_value8").val(field['slider_value8']);
						jQuery("#slider_key9").val(field['slider_key9']); jQuery("#slider_value9").val(field['slider_value9']);
						jQuery("#slider_key10").val(field['slider_key10']); jQuery("#slider_value10").val(field['slider_value10']);
    				});

    			});
    		</script>
    	<?php
    } // end editor_js

    /**
	 * Render custom options for the field
	 *
	 * @since    0.1
	 */
    function slider_settings( $position, $form_id ) {

    	// Create settings on position 1550 (right after range option)
    	if ( 1550 == $position ) {
    		?>
			
    			<li class="slider_value_relations field_setting">
    				<div style="clear:both;">
    					<?php  _e( 'Value Relations', 'gsf-locale' ); ?>
						<?php gform_tooltip( 'number_range' ); ?>
    				</div>
    				<div style="width:50%;float:left"><input type="text" id="slider_min_value_relation" style="width:100%;" onchange="SetFieldProperty('slider_min_value_relation', this.value);" /><label for="slider_min_value_relation"><?php _e( 'Min', 'gsf-locale' ); ?></label></div>
    				<div style="width:50%;float:left"><input type="text" id="slider_max_value_relation" style="width:100%;" onchange="SetFieldProperty('slider_max_value_relation', this.value);" /><label for="slider_max_value_relation"><?php _e( 'Max', 'gsf-locale' ); ?></label></div>
    				<br class="clear">
    			</li>
				
    			<li class="slider_step field_setting">
    				<div style="clear:both;">
    					<?php _e( 'Step', 'gsf-locale' ); ?>
						<?php gform_tooltip( 'slider_step' ); ?>
    				</div>
    				<div style="width:25%;"><input type="number" id="slider_step" step=".01" style="width:100%;" onchange="SetFieldProperty('slider_step', this.value);" /></div>
    			</li>
				<li class="slider_start field_setting">
    				<div style="clear:both;">
    					<?php _e( 'Start', 'gsf-locale' ); ?>
						<?php gform_tooltip( 'slider_start' ); ?>
    				</div>
    				<div style="width:25%;"><input type="number" id="slider_start" value="0" style="width:100%;" onchange="SetFieldProperty('slider_start', this.value);" /></div>
    			</li>

				<li class="slider_step_values field_setting">
    				<div style="clear:both;">
    					<?php _e( 'Step Values / Pool Størrelser', 'gsf-locale' ); ?>
    					<?php gform_tooltip( 'slider_step_values' ); ?>
    				</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">0</label>
						<input type="text" name="slider_key0" id="slider_key0" style="width:45%;" value="Ingen størrelse valgt" disabled />
						<input type="number" name="slider_value0" id="slider_value0" style="width:45%;" value='0' disabled />
					</div>
    				<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">1</label>
						<input type="text" name="slider_key1" id="slider_key1" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key1', this.value);" />
						<input type="number" name="slider_value1" id="slider_value1" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value1', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">2</label>
						<input type="text" name="slider_key2" id="slider_key2" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key2', this.value);" />
						<input type="number" name="slider_value2" id="slider_value2" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value2', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">3</label>
						<input type="text" name="slider_key3" id="slider_key3" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key3', this.value);" />
						<input type="number" name="slider_value3" id="slider_value3" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value3', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">4</label>
						<input type="text" name="slider_key4" id="slider_key4" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key4', this.value);" />
						<input type="number" name="slider_value4" id="slider_value4" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value4', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">5</label>
						<input type="text" name="slider_key5" id="slider_key5" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key5', this.value);" />
						<input type="number" name="slider_value5" id="slider_value5" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value5', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">6</label>
						<input type="text" name="slider_key6" id="slider_key6" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key6', this.value);" />
						<input type="number" name="slider_value6" id="slider_value6" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value6', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">7</label>
						<input type="text" name="slider_key7" id="slider_key7" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key7', this.value);" />
						<input type="number" name="slider_value7" id="slider_value7" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value7', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">8</label>
						<input type="text" name="slider_key8" id="slider_key8" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key8', this.value);" />
						<input type="number" name="slider_value8" id="slider_value8" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value8', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">9</label>
						<input type="text" name="slider_key9" id="slider_key9" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key9', this.value);" />
						<input type="number" name="slider_value9" id="slider_value9" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value9', this.value);" />
					</div>
					<div style="width:100%; display: flex; margin-bottom: 5px;">
						<label style="width: 10%; font-size: 15px;">10</label>
						<input type="text" name="slider_key10" id="slider_key10" style="width:45%;" placeholder="Pool størrelse" onchange="SetFieldProperty('slider_key10', this.value);" />
						<input type="number" name="slider_value10" id="slider_value10" style="width:45%;" placeholder="Pris" onchange="SetFieldProperty('slider_value10', this.value);" />
					</div>
    			</li>
    			
    		<?php
    	}
    } // end slider_settings

	/**
     * Add merge tags to calculation drop down
     *
     * @since    1.5
     */
	function slider_calculation_merge_tags( $merge_tags, $form_id, $fields, $element_id ) {

		// check the type of merge tag dropdown
		if ( 'field_calculation_formula' != $element_id ) {
			return $merge_tags;
		}

		foreach ( $fields as $field ) {

			// check the field type as we only want to generate merge tags for list fields
			if ( 'range' != $field->get_input_type() ) {
				continue;
			}

			$merge_tags[] = array( 'label' => $field->label, 'tag' => '{' . $field->label . ':' . $field->id . '}' );

		}

		return $merge_tags;
	} // END slider_calculation_merge_tags

    /**
     * Append min/max relation notes to label in notifications, confirmations and entry detail
     *
     * @since    0.1
     */
    function pre_submission_filter( $form ) {

    	// Loop through form fields
    	foreach ( $form['fields'] as &$field ) {

    		// If a slider is found
    		if ( 'range' == $field['type'] ) {

    			// Set default min/max values, if they do not exist for the field
    			$min = ( isset( $field['rangeMin'] ) && '' != $field['rangeMin'] ) ? $field['rangeMin'] : 0;
				$max = ( isset( $field['rangeMax'] ) && '' != $field['rangeMax'] ) ? $field['rangeMax'] : 10;
    		}

    	}

    	return $form;

    } // pre_submission_filter



}