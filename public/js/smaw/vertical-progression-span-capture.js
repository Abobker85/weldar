/**
 * This script ensures that content from span elements are captured in hidden form fields
 * for the vertical progression field in the SMAW certificate form
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get the span and hidden input elements
    const verticalProgressionSpan = document.getElementById('vertical_progression_range_span');
    const verticalProgressionHidden = document.getElementById('vertical_progression_range');
    
    // Check if elements exist
    if (verticalProgressionSpan && verticalProgressionHidden) {
        // Initial capture of span content to hidden field
        verticalProgressionHidden.value = verticalProgressionSpan.textContent.trim();
        
        // Set up a MutationObserver to watch for changes to the span content
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    // Update the hidden input whenever the span content changes
                    verticalProgressionHidden.value = verticalProgressionSpan.textContent.trim();
                    console.log('Vertical progression span value captured:', verticalProgressionHidden.value);
                }
            });
        });
        
        // Configure and start the observer
        observer.observe(verticalProgressionSpan, { 
            childList: true,
            characterData: true,
            subtree: true
        });
        
        // Also trigger on select element change
        const verticalProgressionSelect = document.getElementById('vertical_progression');
        if (verticalProgressionSelect) {
            verticalProgressionSelect.addEventListener('change', function() {
                if (this.value !== '__manual__') {
                    // Set the new value based on the selection
                    const newValue = this.value === 'Downhill' ? 'Downhill' : 'Uphill';
                    
                    // Update both the span content and the hidden input value
                    verticalProgressionSpan.textContent = newValue;
                    verticalProgressionHidden.value = newValue;
                    
                    console.log('Vertical progression value updated from select:', verticalProgressionHidden.value);
                }
            });
        }
    }
});
