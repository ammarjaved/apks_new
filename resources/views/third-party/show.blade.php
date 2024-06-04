@extends('layouts.app')

@section('css')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700" rel="stylesheet" />

    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/test/css/style.css') }}" /> --}}
    <style>
        input[type='checkbox'],
        input[type='radio'] {
            min-width: 16px !important;
            margin-right: 12px;
        }

        input[type='radio'] {
            border-radius: 50% !important;
        }

        .error {
            color: red;
        }

        label {
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        input,
        select {
            /* color: black !important; */
            margin-bottom: 0px !important;
            margin-top: 1rem;
        }

        .adjust-height {
            height: 70px;
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-  ">
            <div class="row  " style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __('messages.notice_form') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item text-lowercase"><a
                                href="{{ route('third-party-digging.index', app()->getLocale()) }}">{{ __('messages.index') }}</a>
                        </li>
                        <li class="breadcrumb-item active text-lowercase">{{ __('messages.show') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class=" ">

        <div class="container">

            <div class=" form-input ">

                <div class=" card col-md-12 p-4 ">
                    <div class=" ">
                        <h3 class="text-center p-2"></h3>


                        <div class="row">
                            <div class="col-md-4">
                                <label for="zone">QA Status</label>
                            </div>
                            <div class="col-md-4">

                            

                                @if ($data->visit_date != '' )

                                
                                <button type="button" class="btn  text-left form-control {{$data->qa_status == 'Accept' ? 'btn-success' :($data->qa_status == 'Reject' ? 'btn-danger' :'btn-primary') }} " 
                                    data-toggle="dropdown">
                                    {{ $data->qa_status }}

                                </button>
                                <div class="dropdown-menu" role="menu">
                                    @if ($data->qa_status != 'Accept')
                                        <a href="/{{ app()->getLocale() }}/third-party-digging-update-QA-Status?status=Accept&&id={{ $data->id }}"
                                            onclick="return confirm('are you sure?')">
                                            <button type="submit"
                                                class="dropdown-item pl-3 w-100 text-left">Accept</button>
                                        </a>
                                    @endif

                                    @if ($data->qa_status != 'Reject')
                                        <button type="button" class="btn btn-primary dropdown-item" data-id="{{$data->id }}"
                                            data-toggle="modal" data-target="#rejectReasonModal">
                                            Reject
                                        </button>
                                    @endif



                                </div>
                                @else
                                <button type="button" class="btn  text-left form-control" style="background: orange ; color:white"
                                >
                                <strong >Unsurveyed</strong>


                             </button>
                                @endif
                                {{-- <select name="qa_status" id="qa_status" class="form-control" ></select> --}}
                            </div>
                        </div>

                        @if ($data->qa_status == 'Reject')
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="zone">Reason</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="" id="" cols="10" rows="4" disabled class="form-control">{{$data->reject_remarks}}</textarea>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4"><label for="zone">{{ __('messages.zone') }}</label></div>
                            <div class="col-md-4"><input readonly value="{{ $data->zone }}" class="form-control">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="ba">{{ __('messages.ba') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->ba }}" class="form-control">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4"><label for="digging">{{ __('messages.digging') }}</label></div>
                            <div class="col-md-2">
                                <input type="radio" name="digging" id="digging_yes" class="mt-0" value="yes"
                                    {{ $data->digging == 'yes' ? 'checked' : '' }} disabled>
                                <label for="digging_yes">Yes</label>

                            </div>
                            <div class="col-md-2">
                                <input type="radio" name="digging" id="digging_no" class="mt-0" value="no"
                                    {{ $data->digging == 'no' ? 'checked' : '' }} disabled>
                                <label for="digging_no">No</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="notice">{{ __('messages.notice') }}</label></div>
                            <div class="col-md-2">
                                <input type="radio" name="notice" id="notice_yes" class="mt-0" value="yes"
                                    {{ $data->notice == 'yes' ? 'checked' : '' }} disabled>
                                <label for="notice_yes">Yes</label>

                            </div>
                            <div class="col-md-2">
                                <input type="radio" name="notice" id="notice_no" class="mt-0" value="no"
                                    {{ $data->notice == 'no' ? 'checked' : '' }} disabled>
                                <label for="notice_no">No</label>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="supervision">{{ __('messages.supervision') }}</label></div>
                            <div class="col-md-2">
                                <input type="radio" name="supervision" {{ $data->supervision == 'yes' ? 'checked' : '' }}
                                    id="supervision_yes" class="mt-0" value="yes" disabled>
                                <label for="supervision_yes">Yes</label>

                            </div>
                            <div class="col-md-2">
                                <input type="radio" name="supervision" {{ $data->supervision == 'no' ? 'checked' : '' }}
                                    id="supervision_no" class="mt-0" value="no" disabled>
                                <label for="supervision_no">No</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="wp_name">{{ __('messages.wp_name') }}e</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->wp_name }}" class="form-control">


                            </div>
                        </div>






                        <div class="row">
                            <div class="col-md-4"><label for="survey_date">{{ __('messages.survey_date') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->survey_date }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="patrolling_time">{{ __('messages.patrolling_time') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ date('H:i:s', strtotime($data->patrolling_time)) }}"
                                    class="form-control">
                            </div>
                        </div>









                        <div class="row">
                            <div class="col-md-4"><label for="company_name">{{ __('messages.company_name') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->company_name }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label
                                    for="office_phone_no">{{ __('messages.office_phone_no') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->office_phone_no }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label
                                    for="main_contractor">{{ __('messages.main_contractor') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->main_contractor }}" class="form-control">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label
                                    for="developer_phone_no">{{ __('messages.developer_phone_no') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->developer_phone_no }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label
                                    for="contractor_company_name">{{ __('messages.contractort_company_name') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->contractor_company_name }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="site_supervisor_name">{{ __('messages.site_supervisor_name') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->site_supervisor_name }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label
                                    for="site_supervisor_phone_no">{{ __('messages.site_supervisor_phone_no') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->site_supervisor_phone_no }}" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="excavator_operator_name">{{ __('messages.excavator_operator_name') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->excavator_operator_name }}" class="form-control">
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-4"><label
                                    for="excavator_machinery_reg_no">{{ __('messages.excavator_machinery_reg_no') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->excavator_machinery_reg_no }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="department_diging">{{ __('messages.dpt_diging') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->department_diging }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="survey_status">{{ __('messages.survey_status') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->survey_status }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="road_id">{{ __('messages.rd_name') }}</label></div>
                            <div class="col-md-4">
                                <input readonly value="{{ $data->road_name }}" class="form-control">
                            </div>
                        </div>

                        {{-- <div class="row">
                                <div class="col-md-4"><label for="third-party-image-1">{{__("messages.third_party_image")}} 1</label></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->third_party_image_1)) && $data->third_party_image_1 != '')
                                        <a href="{{ URL::asset($data->third_party_image_1) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->third_party_image_1) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                                @else
                                                <strong>{{__('messages.no_image_found')}}</strong>
                                    @endif
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4"><label for="third-party-image-2">{{__("messages.third_party_image")}} 2</label></div>

                                <div class="col-md-4 text-center mb-3">
                                    @if (file_exists(public_path($data->third_party_image_2)) && $data->third_party_image_2 != '')
                                        <a href="{{ URL::asset($data->third_party_image_2) }}" data-lightbox="roadtrip">
                                            <img src="{{ URL::asset($data->third_party_image_2) }}" alt=""
                                                height="70" class="adjust-height ml-5  "></a>
                                                @else
                                                <strong>{{__('messages.no_image_found')}}</strong>
                                    @endif
                                </div>

                            </div> --}}

                        <div class="row">
                            <div class="col-md-4"><label for="before_image1">{{ __('messages.before_image_1') }}</label>
                            </div>

                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->before_image1)) && $data->before_image1 != '')
                                    <a href="{{ URL::asset($data->before_image1) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->before_image1) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="before_image2">{{ __('messages.before_image_2') }}</label>
                            </div>


                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->before_image2)) && $data->before_image2 != '')
                                    <a href="{{ URL::asset($data->before_image2) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->before_image2) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="before_image3">{{ __('messages.before_image_3') }}</label>
                            </div>

                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->before_image3)) && $data->before_image3 != '')
                                    <a href="{{ URL::asset($data->before_image3) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->before_image3) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="during_image1">{{ __('messages.during_image_1') }}</label>
                            </div>


                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->during_image1)) && $data->during_image1 != '')
                                    <a href="{{ URL::asset($data->during_image1) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->during_image1) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="during_image1">{{ __('messages.during_image_2') }}</label>
                            </div>


                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->during_image2)) && $data->during_image2 != '')
                                    <a href="{{ URL::asset($data->during_image2) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->during_image2) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="during_image1">{{ __('messages.during_image_3') }}</label>
                            </div>


                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->during_image3)) && $data->during_image3 != '')
                                    <a href="{{ URL::asset($data->during_image3) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->during_image3) }}" alt="" height="70"
                                            class="adjust-heigh ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><label for="after_image1">{{ __('messages.after_image_1') }}</label>
                            </div>


                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->after_image1)) && $data->after_image1 != '')
                                    <a href="{{ URL::asset($data->after_image1) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->after_image1) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="after_image2">{{ __('messages.after_image_2') }}</label>
                            </div>

                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->after_image2)) && $data->after_image2 != '')
                                    <a href="{{ URL::asset($data->after_image2) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->after_image2) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label for="after_image3">{{ __('messages.after_image_3') }}</label>
                            </div>



                            <div class="col-md-4 text-center mb-3">
                                @if (file_exists(public_path($data->after_image3)) && $data->after_image3 != '')
                                    <a href="{{ URL::asset($data->after_image3) }}" data-lightbox="roadtrip">
                                        <img src="{{ URL::asset($data->after_image3) }}" alt="" height="70"
                                            class="adjust-height ml-5  "></a>
                                @else
                                    <strong>{{ __('messages.no_image_found') }}</strong>
                                @endif
                            </div>
                        </div>







                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-reject-modal />

@endsection



@section('script')

<script>
    $(function(){
        $('#rejectReasonModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            $('#reject-foam').attr('action', `/{{app()->getLocale()}}/third-party-digging-update-QA-Status`)
            $('#reject-id').val(id);
        });
    })
</script>
@endsection