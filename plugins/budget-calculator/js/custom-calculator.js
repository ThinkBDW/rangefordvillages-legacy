jQuery(document).ready(function($) {
    // Map text for radio button selection
    var textMap = {
        "Weekly": "Week",
        "Monthly": "Month",
        "Yearly": "Year"
    };

    function updateSpanText() {
        var selectedValue = $('input[name="condition"]:checked').val();
        var newText = textMap[selectedValue];

        $('.change-year').each(function() {
            $(this).text(newText);
        });
    }
     // Handle radio button changes
     $('input[name="condition"]').change(function() {
        updateSpanText();
        updateSliderMaxValues(); // New function to update max values
        updateValuesBasedOnRadioButtons();
      
    });

    // Initial text update
    updateSpanText();

    function updateSliderMaxValues() {
        var selectedValue = $('input[name="condition"]:checked').val();

        $('.range-slider-section').each(function() {
            var maxValue = $(this).data(selectedValue.toLowerCase());
            $(this).find('.range-slider').attr('max', maxValue);
        });
    }

    function updateInputValuesBasedOnBedrooms() {
        var selectedBedrooms = $('#property_type_dropdown').val();

        $('.range-slider-section').each(function() {
            var section = $(this).attr('class').split(' ')[1];

            $('[id^=inspired_' + section + ']').each(function() {
                var $inspiredInput = $(this);
                var newValue = parseFloat($inspiredInput.data('value-' + selectedBedrooms));

                var reduceRate = $inspiredInput.data('reduce-rate');
                var reduceRateValue = parseFloat($inspiredInput.data('reduce-value'));

                if (reduceRate) {
                    newValue *= (1 - (reduceRateValue / 100));
                }

                $inspiredInput.val('£' + newValue.toFixed(2));
                $inspiredInput.data('original-value', newValue);
            });
        });

        // Update the values and calculations based on the new values
        updateValuesBasedOnRadioButtons();
    }

    function updateValues(index, section) {
        setTimeout(function() {
        var $inspiredInput = $('#inspired_' + section + '_' + index);
        var $rangeSlider = $('#rangeSlider_' + section + '_' + index);
        var $rangeSliderValue = $('#rangeSliderValue_' + section + '_' + index);
        var originalValue = parseFloat($inspiredInput.data('original-value'));

        
       
        // Ensure we get the value correctly
        var reducevalue12 = parseFloat($inspiredInput.attr('data-reduced-value'));

        // console.log(reducevalue12+'reducevalue12');
       
       
        var sliderOriginalValue = parseFloat($rangeSlider.val());
      
        
      
        var selectedValue = $('input[name="condition"]:checked').val();
        var newValue, newSliderValue, newreducevalue;
    
        if (selectedValue === 'Weekly') {
            newValue = originalValue / 4;
            newreducevalue = reducevalue12 / 4;
            newSliderValue = sliderOriginalValue / 4;
            
        } else if (selectedValue === 'Monthly') {
            newValue = originalValue;
            newreducevalue = reducevalue12;
            newSliderValue = sliderOriginalValue;
        } else if (selectedValue === 'Yearly') {
            newValue = originalValue * 12;
            newreducevalue = reducevalue12 * 12;
            newSliderValue = sliderOriginalValue * 12;
        }
    
        var reduceRate = $inspiredInput.data('reduce-rate');
        var reduceRateValue = parseFloat($inspiredInput.data('reduce-value'));
        
        if (reduceRate) {
            $inspiredInput.val('£' + newreducevalue.toFixed(2));
            
        } else {
            $inspiredInput.val('£' + newValue.toFixed(2));
        }

        $rangeSliderValue.text(newSliderValue.toFixed(2));
        
        
        // Update the total calculation for this section
       

          
        updateTotal(section);

    }, 500); // Delay in milliseconds (1000 ms = 1 second)


    }
    

    function updateValuesBasedOnRadioButtons() {
        $('[id^=rangeSlider_]').each(function() {
            var parts = $(this).attr('id').split('_');
            var section = parts[1];
            var index = parts[2];
            //updateSliderValue($(this));
            //updateValues(index, section);
            updateValues(index, section);
            updatesliderrangevalue($(this));
            
        });

        calculateOverallTotal();
        calculateOverallTotalRight();
    }

    // function updateTotal(section) {
    //     var sectionTotal = 0;
    //     var sectionTotalRight = 0;

    //     $('[id^=rangeSliderValue_' + section + ']').each(function() {
    //         sectionTotal += parseFloat($(this).text());
    //     });

    //     $('#sectionTotal_' + section).text(sectionTotal.toFixed(2));
    //     updateSectionInput(section, sectionTotal);
    //     calculateOverallTotal();

    //     $('[id^=inspired_' + section + ']').each(function() {
    //         sectionTotalRight += parseFloat($(this).val().replace('£', ''));
    //     });

    //     $('#sectionTotalright_' + section).text(sectionTotalRight.toFixed(2));
    //     updateSectionInputRight(section, sectionTotalRight);
    //     calculateOverallTotalRight();
    // }


    function updateTotal(section) {
        setTimeout(function() {

        var sectionTotal = 0;
        var sectionTotalRight = 0;
    
        // Calculate section total from range slider values
        $('[id^=rangeSliderValue_' + section + ']').each(function() {
            sectionTotal += parseFloat($(this).text());
        });
    
        $('#sectionTotal_' + section).text(sectionTotal.toFixed(2));
        updateSectionInput(section, sectionTotal);
        calculateOverallTotal();
    
        // Calculate section total right from inspired input values
        $('[id^=inspired_' + section + ']').each(function() {
            var $inspiredInput = $(this);
            var reduceRate = $inspiredInput.data('reduce-rate');
            var inspiredInputId = $inspiredInput.attr('id');

           // console.log("Element ID: ", $inspiredInput.attr('id')); // Log the ID attribute

            var reducedValueStr = $inspiredInput.data('reduced-value'); // Use attr() to get the attribute value as string
           
            var reducedValueStr = $inspiredInput.val();
            var numericValueStr = reducedValueStr.replace('£', ''); // Remove the £ symbol
            // console.log(numericValueStr+'Reducevalye');
            var reducedValue = parseFloat(numericValueStr); // Parse the string to float
            var originalValue = parseFloat($inspiredInput.val().replace('£', ''));
    
            if (reduceRate) {
                sectionTotalRight += reducedValue;
            } else {
                sectionTotalRight += originalValue;
            }



        });

    
        $('#sectionTotalright_' + section).text(sectionTotalRight.toFixed(2));
        updateSectionInputRight(section, sectionTotalRight);
        calculateOverallTotalRight();
    }, 1000); // Delay in milliseconds (1000 ms = 1 second)

    }

    function calculateOverallTotal() {
        var total = 0;
        $('.section-total span').each(function() {
            total += parseFloat($(this).text());
        });
        $('#Home').val(total.toFixed(2));
        updateTotalValueInChartSection(total);
    }

    function calculateOverallTotalRight() {
        var total = 0;
        $('.section-total-right span').each(function() {
            total += parseFloat($(this).text());
        });
        $('#Home2').val(total.toFixed(2));
        updateTotalValueInChartSectionRight(total);
    }

    function updateTotalValueInChartSection(total) {
        var selectedValue = $('input[name="condition"]:checked').val();
        var periodText = selectedValue === 'Weekly' ? 'week' : selectedValue === 'Monthly' ? 'month' : 'year';
        $('.toal-value-chart').text('£' + total.toFixed(2) + ' per ' + periodText);
    }

    function updateTotalValueInChartSectionRight(total) {
        var selectedValue = $('input[name="condition"]:checked').val();
        var periodText = selectedValue === 'Weekly' ? 'week' : selectedValue === 'Monthly' ? 'month' : 'year';
        $('.toal-value-chart-second').text('£' + total.toFixed(2) + ' per ' + periodText);
    }

    function updateSectionInput(section, total) {
        $('#Input_' + section).val(total.toFixed(2));
        $('#Input_formnew' + section).val(total.toFixed(2));
        updateChart();
    }

    function updateSectionInputRight(section, total) {
        $('#Input_secon' + section).val(total.toFixed(2));
        $('#Input_form' + section).val(total.toFixed(2));
        updateChart2();
    }

    function updateChart() {
        var updatedValues = [];
        $('.dynamic-input').each(function() {
            updatedValues.push(parseFloat($(this).val()));
        });

        pieChart.data.datasets[0].data = updatedValues;
        pieChart.update();
    }

    function updateChart2() {
        var updatedValues = [];
        $('.dynamic-input-second').each(function() {
            updatedValues.push(parseFloat($(this).val()));
        });

        pieChart2.data.datasets[0].data = updatedValues;
        pieChart2.update();
    }

    // function updateSliderValue(slider) {

    //     setTimeout(function() {

    //     var sliderValue = slider.siblings('.slider-value').find('.range-slider-value');
    //     var newValue = parseFloat(slider.val());


    //     var inspiredInput = slider.closest('.range-slider-row').find('.inspired_input');
    //     var reduceRate = inspiredInput.data('reduce-rate');
    //     var reduceRateValue = parseFloat(inspiredInput.data('reduce-value'));
    //     var selectedBedrooms = $('#property_type_dropdown').val();

    //     var ids = inspiredInput.attr('id');
            
    //     // var reducevalue12 = parseFloat(inspiredInput.attr('data-reduced-value'));
    //     //     console.log('--'+ids+'---');
    //     var reducevalue12 = jQuery("#"+ids).attr('data-reduced-value');
    //     console.log('beofre--'+reducevalue12+'------');

    //     var reducevalue121 = parseFloat(reducevalue12);

    //     console.log(reducevalue121+'------');

        

    //     var selectedValue = $('input[name="condition"]:checked').val();
    //     var newValue, newSliderValue, newreducevalue;
    
    //     if (selectedValue === 'Weekly') {
            
    //         newreducevalue = reducevalue12 / 4;
            
    //     } else if (selectedValue === 'Monthly') {
            
    //         newreducevalue = reducevalue12;
            
    //     } else if (selectedValue === 'Yearly') {
            
    //         newreducevalue = reducevalue12 * 12;
            
    //     }

    //     if (reduceRate === true) {
    //         newValue *= (1 - (reduceRateValue / 100));
    //         //console.log(newValue);
    //         if(newreducevalue){
    //             inspiredInput.attr('data-reduced-value', newreducevalue); // Store the reduced value
                
    //            // inspiredInput.text(newreducevalue); // Store the reduced value

    //         }else if(newValue){
    //             inspiredInput.attr('data-reduced-value', newValue.toFixed(2)); // Store the reduced value
    //             //inspiredInput.text(newValue.toFixed(2)); // Store the reduced value

    //         }
    //          //jQuery("#"+ids).attr('data-reduced-value1',reducevalue12);

    //     } else {
    //         newValue = parseFloat(inspiredInput.data('value-' + selectedBedrooms)); // Use the original value for the selected number of bedrooms if reduce_rate is not checked
    //         inspiredInput.attr('data-reduced-value', ''); // Clear the reduced value if reduce_rate is not true
    //     }
    
    //     sliderValue.text(newValue.toFixed(2));
    //     inspiredInput.val('£' + newValue.toFixed(2)); // Update inspired_input with the correct value
    //     updateSectionTotal(slider);
    
    //     // Update the totals and chart values
    //     calculateOverallTotal();
    //     calculateOverallTotalRight();
    // }, 600); // Delay in milliseconds (1000 ms = 1 second)

    // }
    
    function updateSliderValue(slider) {
        
        setTimeout(function() {
            var sliderValueElement = slider.siblings('.slider-value').find('.range-slider-value');
            var sliderValue = parseFloat(slider.val());
    
            var inspiredInput = slider.closest('.range-slider-row').find('.inspired_input');
            var reduceRate = inspiredInput.data('reduce-rate');
            var reduceRateValue = parseFloat(inspiredInput.data('reduce-value'));
            var selectedBedrooms = $('#property_type_dropdown').val();
    
            var newValue;
            if (reduceRate) {
                newValue = sliderValue * (1 - (reduceRateValue / 100));
                inspiredInput.attr('data-reduced-value', newValue.toFixed(2)); // Store the reduced value
            } else {
                newValue = parseFloat(inspiredInput.data('value-' + selectedBedrooms)); // Use the original value for the selected number of bedrooms if reduce_rate is not checked
                inspiredInput.attr('data-reduced-value', ''); // Clear the reduced value if reduce_rate is not true
            }
            
            sliderValueElement.text(newValue.toFixed(2));
            inspiredInput.val('£' + newValue.toFixed(2)); // Update inspired_input with the correct value
            updateSectionTotal(slider);
    
            // Update the totals and chart values
            calculateOverallTotal();
            calculateOverallTotalRight();
        }, 600); // Delay in milliseconds (600 ms = 0.6 second)
    }
    
    
    function updatesliderrangevalue(rangesliderval){
        
        var rangeWrap = rangesliderval.closest('.range-wrap');
        var rangeValueElement = rangeWrap.find('.range-value');
        var valueElement = rangesliderval.siblings('.slider-value').find('.range-slider-value');
        
        var min = rangesliderval.attr('min');
      
        var max = rangesliderval.attr('max');
        var value = rangesliderval.val();
        
        valueElement.text(value);

        var percentage = ((value - min) / (max - min)) * 100;
        
        rangeValueElement.css('width', percentage + '%');
    }


    function updateSectionTotal(slider) {
        
        var section = slider.closest('.range-slider-section');
        var sectionClass = section.attr('class').split(' ')[1];
        var totalElement = $('#sectionTotal_' + sectionClass);
        var totalRightElement = $('#sectionTotalright_' + sectionClass);
    
        var total = 0;
        var totalRight = 0;
    
        section.find('.range-slider').each(function() {
            var sliderValue = parseFloat($(this).val());
           
            var inspiredInput = $(this).closest('.range-slider-row').find('.inspired_input');
           
            var reduceRate = inspiredInput.data('reduce-rate') === true || inspiredInput.data('reduce-rate') === 'true';
            var reduceRateValue = parseFloat(inspiredInput.data('reduce-value'));
            var selectedBedrooms = $('#property_type_dropdown').val();
    
            var newValue;
            if (reduceRate) {
                newValue = sliderValue * (1 - (reduceRateValue / 100));
               
            } else {
                newValue = parseFloat(inspiredInput.data('value-' + selectedBedrooms));
                var selectedValue = $('input[name="condition"]:checked').val();
                var newValue, newSliderValue
                if (selectedValue === 'Weekly') {
                    newValue = newValue / 4;
                    //newSliderValue = sliderOriginalValue / 4;
                } else if (selectedValue === 'Monthly') {
                    newValue = newValue;
                    //newSliderValue = sliderOriginalValue;
                } else if (selectedValue === 'Yearly') {
                    newValue = newValue * 12;
                    //newSliderValue = sliderOriginalValue * 12;
                }  
            }
    
            inspiredInput.val('£' + newValue.toFixed(2));
            total += sliderValue;
            totalRight += newValue;
        });
    
        totalElement.text(total.toFixed(2));
        totalRightElement.text(totalRight.toFixed(2));
        updateInputValue(sectionClass, total);
        updateInputValueRight(sectionClass, totalRight);
    }

function updateInputValueRight(sectionClass, total) {
        $('#Input_secon' + sectionClass).val(total.toFixed(2));
        $('#Input_form' + sectionClass).val(total.toFixed(2));
        updateChart2();
}
    function updateInputValue(sectionClass, total) {
        $('#Input_' + sectionClass).val(total.toFixed(2));
        $('#Input_formnew' + sectionClass).val(total.toFixed(2)); // Update hidden field
        updateChart();
    }

    // Handle radio button changes
    $('input[name="condition"]').change(function() {
        updateSpanText();
        updateSliderMaxValues(); // New function to update max values
        updateValuesBasedOnRadioButtons();
    });

    // Handle range slider changes
    $('[id^=rangeSlider_]').on('input', function() {
        updateSliderValue($(this));
    });

    function getLabelValueschartone() {
        let labels = [];
        let labelElements = $('.input-container.chart-one label');
    
        labelElements.each(function() {
            labels.push($(this).text().trim());
        });
    
        return labels;
    }

    function getLabelValuescharttwo() {
        let labels = [];
        let labelElements = $('.input-container.chart-two label');
    
        labelElements.each(function() {
            labels.push($(this).text().trim());
        });
    
        return labels;
    }

    // Initialize charts
    var labels = [];
    var dataValues = [];
    var dataValuesSecond = [];

    $('.dynamic-input').each(function() {
        labels.push($(this).siblings('label').text());
        dataValues.push(parseFloat($(this).val()));
    });

    $('.dynamic-input-second').each(function() {
        dataValuesSecond.push(parseFloat($(this).val()));
    });

    const ctx = $('#ChartOne1')[0].getContext('2d');
    const pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: getLabelValueschartone(),
            datasets: [{
                label: 'Values',
                data: dataValues,
                backgroundColor: ['#6294a8', '#958bc9', '#5c9182', '#e88770', '#c96dc9', '#e5d16e']
            }]
        }
    });

    const ctx2 = $('#Charttwo')[0].getContext('2d');
    const pieChart2 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: getLabelValuescharttwo(),
            datasets: [{
                label: 'Values',
                data: dataValuesSecond,
                backgroundColor: ['#6294a8', '#958bc9', '#5c9182', '#e88770', '#c96dc9', '#e5d16e']
            }]
        }
    });

    // Initial calculation based on default selection
    updateValuesBasedOnRadioButtons();

    // Initial total calculation
    calculateOverallTotal();
    calculateOverallTotalRight();

    // Initial calculation for all sections
    $('.range-slider-section').each(function() {
        var section = $(this).attr('class').split(' ')[1];
        $(this).find('.range-slider').each(function() {
            updateSliderValue($(this));
            
        });
    });

    // Event listener for range sliders
    $('.range-slider').on('input', function() {
        updateSliderValue($(this));
        
    });

    // Attach event listener to update chart when input values change
    $('.dynamic-input').on('input', updateChart);
    $('.dynamic-input-second').on('input', updateChart2);

    // Initial application of reduction after everything is set up
    $('#property_type_dropdown').change(updateInputValuesBasedOnBedrooms);
    $("#capture").click(function() {
        html2canvas(document.getElementById("mainchart")).then(function(canvas) {
            // Convert the canvas to an image
            var img = canvas.toDataURL("image/png");
            // Create a link element
            var link = document.createElement("a");
            link.href = img;
            link.download = 'table.png';
            // Append to the document
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
    
});

// Email send pie chart value 
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.wpcf7-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting immediately

        // Generate image data URL for the charts
        const chart1Canvas = document.getElementById('ChartOne1');
        const chart2Canvas = document.getElementById('Charttwo');
        const chart1Image = chart1Canvas.toDataURL('image/png');
        const chart2Image = chart2Canvas.toDataURL('image/png');

        // Set the chart images to hidden fields
        document.querySelector('input[name="hidden_chart_image1"]').value = chart1Image;
        document.querySelector('input[name="hidden_chart_image2"]').value = chart2Image;

        // First set of dynamic values
        const dynamicInputs1 = document.querySelectorAll('.dynamic-input-second1');
        const hiddenField1 = document.querySelector('input[name="hidden_dynamic_values"]');
        let dynamicValues1 = {
            title: '',
            totalValue: '',
            details: {}
        };
        const title1Element = document.querySelector('.toal-value-chart-second').closest('.input-container').querySelector('h4');
        const totalValue1Element = document.querySelector('.toal-value-chart-second');

        if (title1Element) {
            dynamicValues1.title = title1Element.innerText;
        }
        if (totalValue1Element) {
            dynamicValues1.totalValue = totalValue1Element.innerText;
        }

        dynamicInputs1.forEach(input => {
            const labelElement = document.querySelector(`label[for="Inputsecond_${input.id.replace('Input_form', '')}"]`);
            if (labelElement) {
                dynamicValues1.details[labelElement.innerText] = input.value;
            }
        });

        if (hiddenField1) {
            hiddenField1.value = JSON.stringify(dynamicValues1);
        }
        console.log('Dynamic Values 1:', dynamicValues1); // Debugging statement

        // Second set of dynamic values
        const dynamicInputs2 = document.querySelectorAll('.dynamic-input1');
        const hiddenField2 = document.querySelector('input[name="hidden_dynamic_values_new"]');
        let dynamicValues2 = {
            title: '',
            totalValue: '',
            details: {}
        };

        const title2Element = document.querySelector('.toal-value-chart').closest('.input-container').querySelector('h4');
        const totalValue2Element = document.querySelector('.toal-value-chart');

        if (title2Element) {
            dynamicValues2.title = title2Element.innerText;
        }
        if (totalValue2Element) {
            dynamicValues2.totalValue = totalValue2Element.innerText;
        }

        dynamicInputs2.forEach(input => {
            const labelElement = document.querySelector(`label[for="Input_${input.id.replace('Input_formnew', '')}"]`);
            if (labelElement) {
                dynamicValues2.details[labelElement.innerText] = input.value;
            }
        });

        if (hiddenField2) {
            hiddenField2.value = JSON.stringify(dynamicValues2);
        }
        console.log('Dynamic Values 2:', dynamicValues2); // Debugging statement

        // Finally, submit the form
        //form.submit();
    });
});


