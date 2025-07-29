// Enhanced vertical progression fix
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Vertical Progression Fix: Starting initialization');
    
    // Create debug information display
    const debugInfo = document.createElement('div');
    debugInfo.style.border = '2px solid red';
    debugInfo.style.padding = '10px';
    debugInfo.style.margin = '20px 0';
    debugInfo.style.backgroundColor = '#ffe6e6';
    debugInfo.innerHTML = '<h4>Vertical Progression Debug Information (Remove in Production)</h4>';
    
    // Get the vertical progression dropdown
    const vpSelect = document.getElementById('vertical_progression');
    if (!vpSelect) {
        console.error('Vertical progression select not found!');
        debugInfo.innerHTML += '<p style="color:red;font-weight:bold">ERROR: Vertical progression dropdown not found!</p>';
    } else {
        // Get database values from PHP
        const certificateValue = document.getElementById('vertical_progression_hidden') ? 
                                document.getElementById('vertical_progression_hidden').value : 'Unknown';
        
        const certificateRangeValue = document.getElementById('vertical_progression_range') ? 
                                    document.getElementById('vertical_progression_range').value : 'Unknown';
        
        // Add values to debug display
        const valueInfo = document.createElement('div');
        valueInfo.innerHTML = `<p><strong>Hidden Field Value:</strong> ${certificateValue}</p>`;
        debugInfo.appendChild(valueInfo);
        
        const rangeInfo = document.createElement('div');
        rangeInfo.innerHTML = `<p><strong>Range Field Value:</strong> ${certificateRangeValue}</p>`;
        debugInfo.appendChild(rangeInfo);
        
        // Add dropdown options to debug info
        const optionsInfo = document.createElement('div');
        optionsInfo.innerHTML = '<p><strong>Available Options:</strong></p><ul>';
        
        for (let i = 0; i < vpSelect.options.length; i++) {
            const option = vpSelect.options[i];
            optionsInfo.innerHTML += `<li>Option ${i}: ${option.value} (${option.selected ? 'selected' : 'not selected'})</li>`;
        }
        
        optionsInfo.innerHTML += '</ul>';
        debugInfo.appendChild(optionsInfo);
        
        // Force select appropriate option based on database value
        console.log('Attempting to select option matching database value:', certificateValue);
        
        let foundMatch = false;
        for (let i = 0; i < vpSelect.options.length; i++) {
            const option = vpSelect.options[i];
            
            if (option.value === certificateValue || 
                (certificateValue === 'Downhill' && option.value === 'Downhill') || 
                (certificateValue === 'Downward' && option.value === 'Downhill') ||
                (certificateValue === 'Uphill' && option.value === 'Uphill') ||
                (certificateValue === 'Upward' && option.value === 'Uphill')) {
                
                option.selected = true;
                foundMatch = true;
                console.log('Found matching option:', option.value);
                
                // Add result to debug display
                const matchInfo = document.createElement('div');
                matchInfo.innerHTML = `<p style="color:green;font-weight:bold">✓ Matching option found and selected: ${option.value}</p>`;
                debugInfo.appendChild(matchInfo);
                
                break;
            }
        }
        
        if (!foundMatch && certificateValue && certificateValue !== 'Unknown') {
            console.error('No matching option found for value:', certificateValue);
            
            // Add error to debug display
            const errorInfo = document.createElement('div');
            errorInfo.innerHTML = `<p style="color:red;font-weight:bold">⚠ No matching option found for value: ${certificateValue}</p>`;
            debugInfo.appendChild(errorInfo);
            
            // Try to normalize the value
            const normalizedValue = certificateValue === 'Downward' ? 'Downhill' : 
                                 certificateValue === 'Upward' ? 'Uphill' : certificateValue;
            
            // Attempt with normalized value
            for (let i = 0; i < vpSelect.options.length; i++) {
                if (vpSelect.options[i].value === normalizedValue) {
                    vpSelect.options[i].selected = true;
                    
                    const fixInfo = document.createElement('div');
                    fixInfo.innerHTML = `<p style="color:blue;font-weight:bold">🔄 Using normalized value: ${normalizedValue}</p>`;
                    debugInfo.appendChild(fixInfo);
                    
                    foundMatch = true;
                    break;
                }
            }
            
            // If still no match found, select first non-empty option
            if (!foundMatch) {
                for (let i = 0; i < vpSelect.options.length; i++) {
                    if (vpSelect.options[i].value) {
                        vpSelect.options[i].selected = true;
                        
                        const fallbackInfo = document.createElement('div');
                        fallbackInfo.innerHTML = `<p style="color:orange;font-weight:bold">⚠ Using fallback option: ${vpSelect.options[i].value}</p>`;
                        debugInfo.appendChild(fallbackInfo);
                        
                        break;
                    }
                }
            }
        }
        
        // Update hidden fields after setting selection
        if (typeof updateVerticalProgressionRange === 'function') {
            console.log('Calling updateVerticalProgressionRange to update hidden fields');
            updateVerticalProgressionRange();
            
            // Get updated values after function call
            const updatedHiddenValue = document.getElementById('vertical_progression_hidden') ? 
                                     document.getElementById('vertical_progression_hidden').value : 'Unknown';
            
            const updatedRangeValue = document.getElementById('vertical_progression_range') ? 
                                    document.getElementById('vertical_progression_range').value : 'Unknown';
            
            // Add updated values to debug display
            const updatedInfo = document.createElement('div');
            updatedInfo.innerHTML = `
                <p><strong>After updateVerticalProgressionRange:</strong></p>
                <p>- Hidden Field: ${updatedHiddenValue}</p>
                <p>- Range Field: ${updatedRangeValue}</p>
                <p>- Dropdown Value: ${vpSelect.value}</p>
            `;
            debugInfo.appendChild(updatedInfo);
        } else {
            console.error('updateVerticalProgressionRange function not found!');
            
            const errorInfo = document.createElement('div');
            errorInfo.innerHTML = `<p style="color:red;font-weight:bold">ERROR: updateVerticalProgressionRange function not found!</p>`;
            debugInfo.appendChild(errorInfo);
        }
    }
    
    // Insert debugging div after position-qualification section or in another suitable location
    const insertAfterElement = document.querySelector('.position-qualification') || 
                            document.querySelector('form') || 
                            document.body.firstChild;
    
    if (insertAfterElement && insertAfterElement.parentNode) {
        insertAfterElement.parentNode.insertBefore(debugInfo, insertAfterElement.nextSibling);
        console.log('Debug information added to page');
    } else {
        console.error('Could not find a suitable location to insert debug information');
        document.body.appendChild(debugInfo);
    }
    
    console.log('🔧 Vertical Progression Fix: Initialization complete');
});
