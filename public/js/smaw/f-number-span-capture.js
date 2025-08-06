/**
 * This script ensures that f_number_range_span content is captured in a hidden form field
 * for submission with the form.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Add an event listener to the form submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Get the content of f_number_range_span
            const fNumberRangeSpan = document.getElementById('f_number_range_span');
            const hiddenInput = document.getElementById('f_number_range_span_hidden');
            
            if (fNumberRangeSpan && hiddenInput) {
                hiddenInput.value = fNumberRangeSpan.textContent;
                console.log('Before submission: f_number_range_span_hidden updated to:', hiddenInput.value);
            }
        });
    });
    
    // Also update when the page loads
    const fNumberRangeSpan = document.getElementById('f_number_range_span');
    const hiddenInput = document.getElementById('f_number_range_span_hidden');
    
    if (fNumberRangeSpan && hiddenInput) {
        hiddenInput.value = fNumberRangeSpan.textContent;
        console.log('On load: f_number_range_span_hidden updated to:', hiddenInput.value);
    }
    
    // Monitor for changes to the span content
    if (fNumberRangeSpan) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'characterData' || mutation.type === 'childList') {
                    const hiddenInput = document.getElementById('f_number_range_span_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = fNumberRangeSpan.textContent;
                        console.log('On change: f_number_range_span_hidden updated to:', hiddenInput.value);
                    }
                }
            });
        });
        
        observer.observe(fNumberRangeSpan, { 
            characterData: true,
            childList: true,
            subtree: true 
        });
    }
});
