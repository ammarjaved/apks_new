@if (Session::has('failed'))
<div class="alert {{ Session::get('alert-class', 'alert-secondary') }}" role="alert">
    {{ Session::get('failed') }}

    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif
@if (Session::has('success'))
<div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
    {{ Session::get('success') }}
    <button type="button" class="close border-0 bg-transparent" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
