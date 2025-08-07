@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>GTAW SMAW Welder Qualification Certificate Details</div>
                    <div>
                        <a href="{{ route('gtaw-smaw-certificates.certificate', $certificate->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Print Certificate
                        </a>
                        <a href="{{ route('gtaw-smaw-certificates.card', $certificate->id) }}" class="btn btn-sm btn-success" target="_blank">
                            <i class="fas fa-id-card"></i> Print Card
                        </a>
                        <a href="{{ route('gtaw-smaw-certificates.back-card', $certificate->id) }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-id-card-alt"></i> Print Back Card
                        </a>
                        <a href="{{ route('gtaw-smaw-certificates.edit', $certificate->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('gtaw-smaw-certificates.index') }}" class="btn btn-sm btn-secondary">
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
                                        <dd>GTAW SMAW{{ $certificate->gtaw_smaw_yes ? ' (Selected)' : '' }}</dd>
                                        
                                        <dt>Specimen Type</dt>
                                        <dd>
                                            @if($certificate->plate_specimen || $certificate->plate)
                                                Plate
                                            @elseif($certificate->pipe_specimen || $certificate->pipe)
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
                                        <dt>GTAW SMAW Thickness</dt>
                                        <dd>{{ $certificate->gtaw_smaw_thickness }} mm</dd>
                                        
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
                                            @elseif($certificate->filler_spec == 'A5.18')
                                                AWS A5.18
                                            @elseif($certificate->filler_spec == 'A5.28')
                                                AWS A5.28
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
                                            @elseif($certificate->filler_f_no == 'F6_with_backing')
                                                F-No.6 With Backing
                                            @elseif($certificate->filler_f_no == 'F6_without_backing')
                                                F-No.6 Without Backing
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
                                
                                <div class="col-md-6">
                                    <dl>
                                        <dt>SMAW Process</dt>
                                        <dd>{{ $certificate->smaw_process }}</dd>
                                        
                                        <dt>Oscillation Value</dt>
                                        <dd>{{ $certificate->oscillation_value ?: 'Not specified' }}</dd>
                                    </dl>
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
