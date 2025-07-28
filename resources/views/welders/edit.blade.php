@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Welder: {{ $welder->name }}</h1>
        <a href="{{ route('welders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Welders
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('welders.update', $welder->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Welder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $welder->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="iqama_no" class="form-label">Iqama No</label>
                            <input type="text" class="form-control @error('iqama_no') is-invalid @enderror" id="iqama_no" name="iqama_no" value="{{ old('iqama_no', $welder->iqama_no) }}" >
                            @error('iqama_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="passport_id_no" class="form-label">Passport ID No</label>
                            <input type="text" class="form-control @error('passport_id_no') is-invalid @enderror" id="passport_id_no" name="passport_id_no" value="{{ old('passport_id_no', $welder->passport_id_no) }}">
                            @error('passport_id_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="welder_no" class="form-label">Welder ID</label>
                            <input type="text" class="form-control @error('welder_no') is-invalid @enderror" id="welder_no" name="welder_no" value="{{ old('welder_no', $welder->welder_no) }}">
                            <div class="form-text">Enter a unique Welder ID</div>
                            @error('welder_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id', $welder->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required>
                                <option value="">Select Nationality</option>
                                @foreach($nationalities as $key => $value)
                                    <option value="{{ $key }}" {{ old('nationality', $welder->nationality) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $welder->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $welder->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    @if($welder->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $welder->photo) }}" alt="{{ $welder->name }}" class="img-thumbnail" style="max-height: 100px;">
                            <div class="form-text">Current photo</div>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                    <div class="form-text">Upload a new photo to replace the existing one (JPEG, PNG, JPG, max 2MB)</div>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="additional_info" class="form-label">Additional Information</label>
                    <textarea class="form-control @error('additional_info') is-invalid @enderror" id="additional_info" name="additional_info" rows="3">{{ old('additional_info', $welder->additional_info) }}</textarea>
                    @error('additional_info')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rt_report_serial" class="form-label">RT Report Serial <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('rt_report_serial') is-invalid @enderror" id="rt_report_serial" name="rt_report_serial" value="{{ old('rt_report_serial', $welder->rt_report_serial) }}" required>
                    <div class="form-text">Enter the serial number of the RT Report</div>
                    @error('rt_report_serial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="ut_report" class="form-label">UT Report (PDF)</label>
                    @if(isset($welder->ut_report))
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $welder->ut_report) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file-pdf"></i> View Current UT Report
                            </a>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('ut_report') is-invalid @enderror" id="ut_report" name="ut_report" accept="application/pdf">
                    <div class="form-text">Upload the UT Report as a PDF file (max 5MB)</div>
                    @error('ut_report')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="ut_report_serial" class="form-label">UT Report Serial</label>
                    <input type="text" class="form-control @error('ut_report_serial') is-invalid @enderror" id="ut_report_serial" name="ut_report_serial" value="{{ old('ut_report_serial', $welder->ut_report_serial ?? '') }}">
                    <div class="form-text">Enter the serial number of the UT Report</div>
                    @error('ut_report_serial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Welder</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for all elements with class select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endpush
