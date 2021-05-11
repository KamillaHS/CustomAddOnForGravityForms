src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js";

jQuery(document).ready(function($){
	var key1, key2, key3, key4, key5, key6, key7, key8, key9, key10;
	var val1, val2, val3, val4, val5, val6, val7, val8, val9, val10;
	var input_1, input_2, input_3, input_4, input_5, input_6, input_7, input_8, input_9, input_10;
	var defaultInput = {key: "Ingen størrelse valgt", value: 0};
	var values;
	var values2D = [];
	
	var sliders = document.getElementsByClassName('ginput_container_range_slider');
	console.log(sliders);
	for (let i = 0; i < sliders.length; i++) {
		init(sliders[i], i)
    }
	
	function init(slider, index) {
		let rangeslider_id = slider.firstChild.id;
		let maxValue = document.getElementById('range_maxValue_' + rangeslider_id).innerHTML;
		
		$('.results_' + rangeslider_id).html('max = ' + maxValue + ", id = " + rangeslider_id);
		
		getElements(rangeslider_id);
		setInputValues();
		checkMaxValue(maxValue);
		values2D.push(values);
		addListeners(slider, rangeslider_id, index);
	}
	
	function getElements(rangeslider_id) {
		console.log('range_key1_' + rangeslider_id);
		key1 = document.getElementById('range_key1_' + rangeslider_id).innerHTML;
    	val1 = document.getElementById('range_val1_' + rangeslider_id).innerHTML;
    	key2 = document.getElementById('range_key2_' + rangeslider_id).innerHTML;
    	val2 = document.getElementById('range_val2_' + rangeslider_id).innerHTML;
    	key3 = document.getElementById('range_key3_' + rangeslider_id).innerHTML;
    	val3 = document.getElementById('range_val3_' + rangeslider_id).innerHTML;
    	key4 = document.getElementById('range_key4_' + rangeslider_id).innerHTML;
    	val4 = document.getElementById('range_val4_' + rangeslider_id).innerHTML;
    	key5 = document.getElementById('range_key5_' + rangeslider_id).innerHTML;
    	val5 = document.getElementById('range_val5_' + rangeslider_id).innerHTML;
    	key6 = document.getElementById('range_key6_' + rangeslider_id).innerHTML;
    	val6 = document.getElementById('range_val6_' + rangeslider_id).innerHTML;
    	key7 = document.getElementById('range_key7_' + rangeslider_id).innerHTML;
    	val7 = document.getElementById('range_val7_' + rangeslider_id).innerHTML;
    	key8 = document.getElementById('range_key8_' + rangeslider_id).innerHTML;
    	val8 = document.getElementById('range_val8_' + rangeslider_id).innerHTML;
    	key9 = document.getElementById('range_key9_' + rangeslider_id).innerHTML;
    	val9 = document.getElementById('range_val9_' + rangeslider_id).innerHTML;
    	key10 = document.getElementById('range_key10_' + rangeslider_id).innerHTML;
    	val10 = document.getElementById('range_val10_' + rangeslider_id).innerHTML;
		
    	console.log('key: '+ key1 + " , value: " + val1);
	}
	
	// set input values
	function setInputValues() {
		input_1 = {key: key1, value: val1};
    	input_2 = {key: key2, value: val2};
    	input_3 = {key: key3, value: val3};
    	input_4 = {key: key4, value: val4};
    	input_5 = {key: key5, value: val5};
    	input_6 = {key: key6, value: val6};
    	input_7 = {key: key7, value: val7};
    	input_8 = {key: key8, value: val8};
    	input_9 = {key: key9, value: val9};
    	input_10 = {key: key10, value: val10};
		
		/*
		input_1 = {key: "6 x 3 x 1,5 m (27 m3)", value: 95500};
    	input_2 = {key: "7 x 3 x 1,5 m (31,5 m3)", value: 103000};
    	input_3 = {key: "7 x 3,3 x 1,5 m (34,65 m3)", value: 108500};
		input_4 = {key: "8 x 3,3 x 1,5 m (39,6 m3)", value: 120500};
		input_5 = {key: "8 x 3,6 x 1,5 m (43,2 m3)", value: 123500};
		input_6 = {key: "9 x 3,6 x 1,5 m (44,56 m3)", value: 131200}; 
		input_7 = {key: "10 x 3,6, 1,5 m (54 m3)", value: 138600};
		input_8 = {key: "", value: 0};
		input_9 = {key: "", value: 0};
		input_10 = {key: "", value: 0};
		*/
		
		//console.log(input_1);
	}

	// check maxValue and put input values in literal objects based on the maxValue
	function checkMaxValue(maxValue) {
		if(maxValue == 0) { 
			values = {0: defaultInput};
		} else if(maxValue == 1) {
			values = {0: defaultInput, 1: input_1};
		} else if(maxValue == 2) {
			values = {0: defaultInput, 1: input_1, 2: input_2};
		} else if(maxValue == 3) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3};
		} else if(maxValue == 4) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4};
		} else if(maxValue == 5) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5};
		} else if(maxValue == 6) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6};
		} else if(maxValue == 7) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7};
		} else if(maxValue == 8) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8};
		} else if(maxValue == 9) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8, 9: input_9};
		} else if(maxValue == 10) {
			values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8, 9: input_9, 10: input_10};
		} else {
			values = '';
			console.log('error: the literal objebt named -values- needs a minimum of 0 as maxValue and cannot exceed 10 as maxValue')
    	}
		console.log(values);
	}
    
	function addListeners(slider, rangeslider_id, index) {
		var inp = slider.firstChild;
    
    	var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    	console.log(isMobile);
    	if (isMobile) {
        	inp.addEventListener("touchmove", function () { 
				document.getElementById('text_' + rangeslider_id).innerHTML = values2D[index][this.value].key  + " - " + values2D[index][this.value].value; 
				
        	});
    	} else {
        	inp.addEventListener("mousemove", function () { 
				document.getElementById('text_' + rangeslider_id).innerHTML = values2D[index][this.value].key + " - " + values2D[index][this.value].value; 

        	});
		}
		
    
    	$('#'+rangeslider_id).change(function() {
        	output = [values[this.value].key, values[this.value].value];
        	console.log(output);
		});

		
	}


})