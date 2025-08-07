<div class="modal fade" id="rt-report-modal" tabindex="-1" role="dialog" aria-labelledby="rt-report-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rt-report-modal-label">Upload RT Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rt-report-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="welder_id" id="modal-welder-id">
                    <input type="hidden" name="certificate_id" id="modal-certificate-id">
                    <div class="form-group">
                        <label for="attachment">RT Report File</label>
                        <input type="file" class="form-control-file" id="attachment" name="attachment" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $('#rt-report-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var certificateId = button.data('certificate-id');
        var welderId = button.data('welder-id');
        var certificateType = button.data('certificate-type');

        var modal = $(this);
        modal.find('#modal-certificate-id').val(certificateId);
        modal.find('#modal-welder-id').val(welderId);

        var form = modal.find('#rt-report-form');
        var action = '/';
        if (certificateType === 'smaw') {
            action = '{{ route("smaw-rt-reports.store") }}';
        } else if (certificateType === 'gtaw') {
            action = '{{ route("gtaw-rt-reports.store") }}';
        } else if (certificateType === 'fcaw') {
            action = '{{ route("fcaw-rt-reports.store") }}';
        } else if (certificateType === 'saw') {
            action = '{{ route("saw-rt-reports.store") }}';
        }
        form.attr('action', action);
    });
</script>
@endpush
