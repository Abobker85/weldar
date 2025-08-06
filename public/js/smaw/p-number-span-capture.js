/**
 * This script ensures that p_number_range_span content is captured in a hidden form field
 * for submission with the form.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Add an event listener to the form submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Get the content of p_number_range_span
            const pNumberRangeSpan = document.getElementById('p_number_range_span');
            const hiddenInput = document.getElementById('p_number_range_span_hidden');
            
            if (pNumberRangeSpan && hiddenInput) {
                hiddenInput.value = pNumberRangeSpan.textContent;
                console.log('Before submission: p_number_range_span_hidden updated to:', hiddenInput.value);
            }
        });
    });
    
    // Also update when the page loads
    const pNumberRangeSpan = document.getElementById('p_number_range_span');
    const hiddenInput = document.getElementById('p_number_range_span_hidden');
    
    if (pNumberRangeSpan && hiddenInput) {
        hiddenInput.value = pNumberRangeSpan.textContent;
        console.log('On load: p_number_range_span_hidden updated to:', hiddenInput.value);
    }
    
    // Monitor for changes to the span content
    if (pNumberRangeSpan) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'characterData' || mutation.type === 'childList') {
                    const hiddenInput = document.getElementById('p_number_range_span_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = pNumberRangeSpan.textContent;
                        console.log('On change: p_number_range_span_hidden updated to:', hiddenInput.value);
                    }
                }
            });
        });
        
        observer.observe(pNumberRangeSpan, { 
            characterData: true,
            childList: true,
            subtree: true 
        });
    }
});
