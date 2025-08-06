/**
 * This script handles thickness range calculations for the SMAW certificate form
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get the input element
    const thicknessHidden = document.getElementById('smaw_thickness_range');
    
    // Set initial value if needed
    if (thicknessHidden && (!thicknessHidden.value || thicknessHidden.value === '')) {
        const thicknessInput = document.getElementById('smaw_thickness');
        if (thicknessInput && thicknessInput.value) {
            const thickness = parseFloat(thicknessInput.value);
            if (!isNaN(thickness)) {
                if (thickness <= 12) {
                    thicknessHidden.value = (thickness * 2).toFixed(2) + ' mm';
                    thicknessHidden.setAttribute('value', (thickness * 2).toFixed(2) + ' mm');
                } else {
                    thicknessHidden.value = 'Maximum to be welded';
                    thicknessHidden.setAttribute('value', 'Maximum to be welded');
                }
            }
        }
    }
    
    // Add event listener to thickness input
    const thicknessInput = document.getElementById('smaw_thickness');
    if (thicknessInput) {
        thicknessInput.addEventListener('change', function() {
            if (thicknessHidden) {
                const thickness = parseFloat(this.value);
                if (!isNaN(thickness)) {
                    if (thickness <= 12) {
                        thicknessHidden.value = (thickness * 2).toFixed(2) + ' mm';
                        thicknessHidden.setAttribute('value', (thickness * 2).toFixed(2) + ' mm');
                    } else {
                        thicknessHidden.value = 'Maximum to be welded';
                        thicknessHidden.setAttribute('value', 'Maximum to be welded');
                    }
                    console.log('Thickness range updated after change:', thicknessHidden.value);
                }
            }
        });
    }
});
