jQuery(document).ready(function($){

    var maxValue = document.getElementById('range_maxValue').innerHTML; 
    var rangeslider_id = document.getElementById('range_field_id').innerHTML;

    var key1 = document.getElementById('range_key1').innerHTML;
    var val1 = document.getElementById('range_val1').innerHTML;
    var key2 = document.getElementById('range_key2').innerHTML;
    var val2 = document.getElementById('range_val2').innerHTML;
    var key3 = document.getElementById('range_key3').innerHTML;
    var val3 = document.getElementById('range_val3').innerHTML;
    var key4 = document.getElementById('range_key4').innerHTML;
    var val4 = document.getElementById('range_val4').innerHTML;
    var key5 = document.getElementById('range_key5').innerHTML;
    var val5 = document.getElementById('range_val5').innerHTML;
    var key6 = document.getElementById('range_key6').innerHTML;
    var val6 = document.getElementById('range_val6').innerHTML;
    var key7 = document.getElementById('range_key7').innerHTML;
    var val7 = document.getElementById('range_val7').innerHTML;
    var key8 = document.getElementById('range_key8').innerHTML;
    var val8 = document.getElementById('range_val8').innerHTML;
    var key9 = document.getElementById('range_key9').innerHTML;
    var val9 = document.getElementById('range_val9').innerHTML;
    var key10 = document.getElementById('range_key10').innerHTML;
    var val10 = document.getElementById('range_val10').innerHTML;

    $('.results').html('max = ' + maxValue + ", id = " + rangeslider_id);

    console.log('key: '+ key1 + " , value: " + val1);

    // set input values
    var input_1 = {key: key1, value: val1};
    var input_2 = {key: key2, value: val2};
    var input_3 = {key: key3, value: val3};
    var input_4 = {key: key4, value: val4};
    var input_5 = {key: key5, value: val5};
    var input_6 = {key: key6, value: val6};
    var input_7 = {key: key7, value: val7};
    var input_8 = {key: key8, value: val8};
    var input_9 = {key: key9, value: val9};
    var input_10 = {key: key10, value: val10};

    console.log(input_1);

    var defaultInput = {key: "Ingen størrelse valgt", value: 0};
    /*
	var input_1 = {key: "6 x 3 x 1,5 m (27 m3)", value: 95500};
    var input_2 = {key: "7 x 3 x 1,5 m (31,5 m3)", value: 103000};
    
	var input_3 = {key: "7 x 3,3 x 1,5 m (34,65 m3)", value: 108500};
	var input_4 = {key: "8 x 3,3 x 1,5 m (39,6 m3)", value: 120500};
	var input_5 = {key: "8 x 3,6 x 1,5 m (43,2 m3)", value: 123500};
	var input_6 = {key: "9 x 3,6 x 1,5 m (44,56 m3)", value: 131200}; 
	var input_7 = {key: "10 x 3,6, 1,5 m (54 m3)", value: 138600};
	var input_8 = {key: "", value: 0};
	var input_9 = {key: "", value: 0};
	var input_10 = {key: "", value: 0};
    */

    // check maxValue and put input values in literal objects based on the maxValue
	if(maxValue == 0) { 
		var values = {0: defaultInput};
	} else if(maxValue == 1) {
		var values = {0: defaultInput, 1: input_1};
	} else if(maxValue == 2) {
		var values = {0: defaultInput, 1: input_1, 2: input_2};
	} else if(maxValue == 3) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3};
	} else if(maxValue == 4) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4};
	} else if(maxValue == 5) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5};
	} else if(maxValue == 6) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6};
	} else if(maxValue == 7) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7};
	} else if(maxValue == 8) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8};
	} else if(maxValue == 9) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8, 9: input_9};
	} else if(maxValue == 10) {
		var values = {0: defaultInput, 1: input_1, 2: input_2, 3: input_3, 4: input_4, 5: input_5, 6: input_6, 7: input_7, 8: input_8, 9: input_9, 10: input_10};
	} else {
		var values = '';
		console.log('error: the literal objebt named -values- needs a minimum of 0 as maxValue and cannot exceed 10 as maxValue')
    }

    console.log(values);

    
    var inp = document.getElementById(rangeslider_id); 
    
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    console.log(isMobile);
    if (isMobile) {
        inp.addEventListener("touchmove", function () { 
            document.getElementById('text').innerHTML = values[this.value].key  + " - " + values[this.value].value; 
        });
    } else {
        inp.addEventListener("mousemove", function () { 
            document.getElementById('text').innerHTML = values[this.value].key + " - " + values[this.value].value; 
        });
    }
    
    $('#'+rangeslider_id).change(function() {
        output = [values[this.value].key, values[this.value].value];
        console.log(output);
    });


})