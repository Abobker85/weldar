@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>FCAW Welder Qualification Certificate Details</div>
                    <div>
                        <a href="{{ route('fcaw-certificates.certificate', $certificate->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Print Certificate
                        </a>
                        <a href="{{ route('fcaw-certificates.card', $certificate->id) }}" class="btn btn-sm btn-success" target="_blank">
                            <i class="fas fa-id-card"></i> Print Card
                        </a>
                        <a href="{{ route('fcaw-certificates.back-card', $certificate->id) }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-id-card-alt"></i> Print Back Card
                        </a>
                        <a href="{{ route('fcaw-certificates.edit', $certificate->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('fcaw-certificates.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h4 class="mb-4">Certificate Information</h4>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Certificate Number</dt>
                                        <dd>{{ $certificate->certificate_no }}</dd>
                                        
                                        <dt>Test Date</dt>
                                        <dd>{{ $certificate->test_date->format('Y-m-d') }}</dd>
                                        
                                        <dt>Test Result</dt>
                                        <dd>
                                            <span class="badge badge-{{ $certificate->test_result ? 'success' : 'danger' }}">
                                                {{ $certificate->test_result ? 'Pass' : 'Fail' }}
                                            </span>
                                        </dd>
                                        
                                        <dt>WPS Number</dt>
                                        <dd>{{ $certificate->wps_followed }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Process</dt>
                                        <dd>SMAW{{ $certificate->smaw_yes ? ' (Selected)' : '' }}</dd>
                                        
                                        <dt>Specimen Type</dt>
                                        <dd>
                                            @if($certificate->plate_specimen)
                                                Plate
                                            @elseif($certificate->pipe_specimen)
                                                Pipe
                                            @else
                                                Not specified
                                            @endif
                                        </dd>
                                        
                                        <dt>Test Position</dt>
                                        <dd>{{ $certificate->test_position }}</dd>
                                        
                                        <dt>Position Range</dt>
                                        <dd>{{ $certificate->position_range }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Inspector</dt>
                                        <dd>{{ $certificate->inspector_name ?: 'Not specified' }}</dd>
                                        
                                        <dt>Inspection Date</dt>
                                        <dd>{{ $certificate->inspector_date ? $certificate->inspector_date->format('Y-m-d') : 'Not specified' }}</dd>
                                        
                                        <dt>Created By</dt>
                                        <dd>{{ $certificate->createdBy ? $certificate->createdBy->name : 'System' }}</dd>
                                        
                                        <dt>Creation Date</dt>
                                        <dd>{{ $certificate->created_at->format('Y-m-d H:i') }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Welder & Company Information</h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <dl>
                                        <dt>Welder Name</dt>
                                        <dd>{{ $certificate->welder->name }}</dd>
                                        
                                        <dt>Welder ID</dt>
                                        <dd>{{ $certificate->welder->welder_no }}</dd>
                                        
                                        <dt>Passport/ID Number</dt>
                                        <dd>{{ $certificate->welder->passport_id_no ?: 'Not specified' }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-6">
                                    <dl>
                                        <dt>Company</dt>
                                        <dd>{{ $certificate->company->name }}</dd>
                                        
                                        <dt>Company Code</dt>
                                        <dd>{{ $certificate->company->code ?: 'Not specified' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Material & Welding Information</h5>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Base Metal Specification</dt>
                                        <dd>{{ $certificate->base_metal_spec }}</dd>
                                        
                                        <dt>Base Metal P-Number</dt>
                                        <dd>{{ $certificate->base_metal_p_no }}</dd>
                                        
                                        <dt>P-Number Range</dt>
                                        <dd>{{ $certificate->p_number_range }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>SMAW Thickness</dt>
                                        <dd>{{ $certificate->smaw_thickness }} mm</dd>
                                        
                                        <dt>Backing</dt>
                                        <dd>{{ $certificate->backing }}</dd>
                                        
                                        <dt>Backing Range</dt>
                                        <dd>{{ $certificate->backing_range }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Pipe Diameter</dt>
                                        <dd>
                                            @if($certificate->pipe_diameter_type == '__manual__')
                                                {{ $certificate->pipe_diameter_manual }}
                                            @elseif($certificate->pipe_diameter_type == '8_nps')
                                                8" NPS (219.1 mm)
                                            @elseif($certificate->pipe_diameter_type == '6_nps')
                                                6" NPS (168.3 mm)
                                            @elseif($certificate->pipe_diameter_type == '4_nps')
                                                4" NPS (114.3 mm)
                                            @elseif($certificate->pipe_diameter_type == '2_nps')
                                                2" NPS (60.3 mm)
                                            @else
                                                {{ $certificate->pipe_diameter_type }}
                                            @endif
                                        </dd>
                                        
                                        <dt>Diameter Range</dt>
                                        <dd>{{ $certificate->diameter_range }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Filler Metal Information</h5>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>AWS Specification</dt>
                                        <dd>
                                            @if($certificate->filler_spec == '__manual__')
                                                {{ $certificate->filler_spec_manual }}
                                            @elseif($certificate->filler_spec == '5.1')
                                                AWS A5.1
                                            @elseif($certificate->filler_spec == '5.4')
                                                AWS A5.4
                                            @elseif($certificate->filler_spec == '5.5')
                                                AWS A5.5
                                            @else
                                                {{ $certificate->filler_spec }}
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>Classification</dt>
                                        <dd>
                                            @if($certificate->filler_class == '__manual__')
                                                {{ $certificate->filler_class_manual }}
                                            @else
                                                {{ $certificate->filler_class }}
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                                
                                <div class="col-md-4">
                                    <dl>
                                        <dt>F-Number</dt>
                                        <dd>
                                            @if($certificate->filler_f_no == '__manual__')
                                                {{ $certificate->filler_f_no_manual }}
                                            @elseif($certificate->filler_f_no == 'F4_with_backing')
                                                F-No.4 With Backing
                                            @elseif($certificate->filler_f_no == 'F5_with_backing')
                                                F-No.5 With Backing
                                            @elseif($certificate->filler_f_no == 'F4_without_backing')
                                                F-No.4 Without Backing
                                            @elseif($certificate->filler_f_no == 'F5_without_backing')
                                                F-No.5 Without Backing
                                            @elseif($certificate->filler_f_no == 'F43')
                                                F-No. 43
                                            @else
                                                {{ $certificate->filler_f_no }}
                                            @endif
                                        </dd>
                                        
                                        <dt>F-Number Range</dt>
                                        <dd>{{ $certificate->f_number_range }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Additional Information</h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <dl>
                                        <dt>Vertical Progression</dt>
                                        <dd>{{ $certificate->vertical_progression }}</dd>
                                        
                                        <dt>Vertical Progression Range</dt>
                                        <dd>{{ $certificate->vertical_progression_range }}</dd>
                                    </dl>
                                </div>
                            </div>

                            <hr>
                            
                            <!-- RT Reports Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>RT Reports</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date Uploaded</th>
                                                    <th>File</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($certificate->rtReports as $report)
                                                    <tr>
                                                        <td>{{ $report->created_at->format('Y-m-d') }}</td>
                                                        <td>{{ basename($report->attachment) }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/' . $report->attachment) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No RT reports uploaded yet</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#rt-report-modal" data-certificate-id="{{ $certificate->id }}" data-welder-id="{{ $certificate->welder_id }}" data-certificate-type="fcaw">
                                        <i class="fas fa-upload"></i> Upload RT Report
                                    </button>
                                </div>
                            </div>
                            
                            <div class="detail-row">
    <div class="detail-label">Dia:</div>
    <div class="detail-value">{{ $certificate->diameter ?? 'N/A' }}</div>
</div>

<div class="detail-row">
    <div class="detail-label">Thickness:</div>
    <div class="detail-value">{{ $certificate->smaw_thickness ?? 'N/A' }} mm</div>
</div>

<div class="detail-row">
    <div class="detail-label">Test Date:</div>
    <div class="detail-value">{{ $certificate->test_date ? $certificate->test_date->format('d F, Y') : 'N/A' }}</div>
</div>

<!-- Signature Section -->
<div class="section-card">
    <div class="section-header">Signatures</div>
    <div class="section-content">
        <div class="row">
            <div class="col-md-6">
                <h5>Welder's Signature</h5>
                @if($certificate->signature_data)
                    <img src="{{ $certificate->signature_data }}" alt="Welder's Signature" class="signature-image">
                @else
                    <p class="text-muted">No signature provided</p>
                @endif
            </div>
            <div class="col-md-6">
                <h5>Inspector's Signature</h5>
                @if($certificate->inspector_signature_data)
                    <img src="{{ $certificate->inspector_signature_data }}" alt="Inspector's Signature" class="signature-image">
                @else
                    <p class="text-muted">No signature provided</p>
                @endif
            </div>
        </div>
    </div>
</div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">Welder Photo</div>
                                <div class="card-body text-center">
                                    @if($certificate->photo_path)
                                        <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="Welder Photo" class="img-fluid mb-2" style="max-height: 200px;">
                                    @else
                                        <div class="bg-light p-5 text-muted">
                                            <i class="fas fa-user fa-3x mb-2"></i><br>
                                            No photo available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
