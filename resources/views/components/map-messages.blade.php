@extends('layouts.app')

@section('content')
    <style>
        .navbar {
            display: none !important
        }
    </style>
    <section class="content">
        <div class="container-fluid">

            @if ($success == false)

                <div class="alert alert-class alert-secondary " role="alert">
                    Some thing is wrong try again later

                    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
            @endif
            @if ($success == true)
                <div class="alert  alert-class   alert-success  " role="alert">
                    Form Update Successfully
                    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close" onclick="clearPage()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </section>

    <h6>For detail click on detail button <a href="{{ route($url.'.show', [app()->getLocale(), $id]) }}"
            target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">Detail</a>
    </h6>
@endsection

@section('script')

<script>
    function clearPage() {
        $('h6').html('');
    }
</script>

@endsection
