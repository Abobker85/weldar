<div class="welder-select-container">
    <input type="text" class="welder-search" id="welder_search"
        placeholder="Search welder by name or ID...">
    <div class="welder-results" id="welder_results">
        @foreach ($welders as $welder)
            <div class="welder-item" data-id="{{ $welder->id }}">
                {{ $welder->name }} (ID: {{ $welder->iqama_no }})
            </div>
        @endforeach
    </div>
    <select class="form-select" name="welder_id" id="welder_id" required
        style="font-weight: bold; display: none;">
        <option value="">-- Select Welder --</option>
        @foreach ($welders as $welder)
            <option value="{{ $welder->id }}"
                {{ $selectedWelder && $selectedWelder->id == $welder->id ? 'selected' : '' }}>
                {{ $welder->name }}
            </option>
        @endforeach
    </select>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Connect the search input with dropdown
        $('#welder_search').on('input', function() {
            let term = $(this).val();
            
            // Open dropdown if there's input
            if (term.length > 0) {
                $('#welder_results').show();
                
                // Filter items
                $('.welder-item').each(function() {
                    if ($(this).text().toLowerCase().includes(term.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                $('#welder_results').hide();
            }
        });
        
        // Handle item selection
        $('.welder-item').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).text().trim();
            
            // Update the hidden select
            $('#welder_id').val(id);
            
            // Update the search input
            $('#welder_search').val(name);
            
            // Hide dropdown
            $('#welder_results').hide();
            
            // Load welder data
            loadWelderData(id);
        });
    });
</script>
@endpush
            