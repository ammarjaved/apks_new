@extends('layouts.app', ['page_title' => 'Index'])


@section('content')
    <section class="content-header pb-0">
        <div class="container-  ">
            <div class="row  mb-0 pb-0" style="flex-wrap:nowrap">
                <div class="col-sm-6">
                    <h3>{{ __("messages.$title") }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a
                                href="/{{ app()->getLocale() }}/dashboard">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">LKS </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <section class="content-">
        <div class="container-fluid">
            @include('components.message')


            <div class="row">
                @include('components.lks-filter')

                <div class="col-12-">
                    <div class="card">



                        <div class="card-body" id="lks_dat">


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@section('script')

<script>
    function submitForm(param){
        let url =`/{{app()->getLocale()}}/${param}`
        $('#generate-lks-form').attr('action',url)
        $('#generate-lks-form').submit();
console.log(param);
    }
 
</script>
@endsection



