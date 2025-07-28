+@include('smaw_certificates.partials.welding-variables')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all backing range values immediately
    if (document.getElementById('backing')) {
        updateBackingRange();
    }
    
    // Add event listener for backing manual input
    const backingManual = document.getElementById('backing_manual');
    if (backingManual) {
        backingManual.addEventListener('input', function() {
            if (document.getElementById('backing').value === '__manual__') {
                updateBackingRange();
            }
        });
    }
    
    // Toggle visibility of manual input field when dropdown changes
    document.getElementById('backing')?.addEventListener('change', function() {
        const manualInput = document.getElementById('backing_manual');
        if (manualInput) {
            manualInput.style.display = this.value === '__manual__' ? 'block' : 'none';
            if (this.value === '__manual__') {
                manualInput.focus();
            }
        }
    });
});

/**
 * Helper function to update backing range span with proper format
 */
function ensureBackingRangeDisplay() {
    setTimeout(function() {
        const backingRangeSpan = document.getElementById('backing_range_span');
        const backingRange = document.getElementById('backing_range');
        if (backingRangeSpan && backingRange && backingRange.value) {
            backingRangeSpan.textContent = backingRange.value;
        }
    }, 100);
}
</script>
