<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="https://malsup.github.io/jquery.form.js"></script>
<script>
    var $jq = $.noConflict(true);
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<link rel="stylesheet" href="{{ URL::asset('assets/lib/images_slider/css-view/lightbox.css') }}">
<script src="{{ URL::asset('assets/lib/images_slider/js-view/lightbox-2.6.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/pannellum/pannellum.css') }}" />

<script src="{{ URL::asset('assets/pannellum/pannellum.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/pannellum/lib/window-engine.css') }}" />
<script src="{{ URL::asset('assets/pannellum/lib/window-engine.js') }}"></script>

<style>
    .leaflet-control-attribution.leaflet-control {
        display: none;
    }

    input {
        min-width: 16px !important;
    }



    .side-bar::-webkit-scrollbar,
    .lb-outerContainer,
    .lb-closeContainer {
        display: none;
    }

    #panorama {
        width: 400px;
        height: 400px;
    }

    .windowGroup {
        z-index: 99999;
    }

    .form-input {
        border: 0
    }

    input[type="radio"] {
        border-radius: 50% !important;
    }


    input[type="radio"].without_defects {

        background-color: #00F700;
        border-color: #00F700;
    }

    input[type="radio"]:checked.without_defects {
        background-color: #00F700;
        border-color: #00F700;
    }


    input[type="radio"].with_defects {

        background-color: #F7F701;
        border-color: #F7F701;
    }

    input[type="radio"]:checked.with_defects {
        background-color: #F7F701;
        border-color: #F7F701;
    }


    input[type="radio"].reject {

        background-color: #FF0000;
        border-color: #FF0000;
    }

    input[type="radio"]:checked.reject {
        background-color: #FF0000;
        border-color: #FF0000;
    }

    input[type="radio"].unsurveyed {

background-color: #FF7F00;
border-color: #FF7F00;
}

input[type="radio"]:checked.unsurveyed {
background-color: #FF7F00;
border-color: #FF7F00;
}
    input[type="radio"].pano {

background-color: blue;
border-color: blue;
}

input[type="radio"]:checked.pano {
background-color: blue;
border-color: blue;
}


input[type="radio"].pending {

background-color: #BF2BFF;
border-color: #BF2BFF;
}

input[type="radio"]:checked.pending {
background-color: #BF2BFF;
border-color: #BF2BFF;
}



input[type="radio"].kiv {

background-color: #000000;
border-color: #000000;
}

input[type="radio"]:checked.kiv {
background-color: #000000;
border-color: #000000;
}
    .tt-menu {
    z-index: 9999999999999 !important;
}

    .tt-query,
    /* UPDATE: newer versions use tt-input instead of tt-query */
    .tt-hint {
        width: 200px;
        height: 30px;
        padding: 8px 12px;
        font-size: 24px;
        line-height: 30px;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;
    }

    .tt-query {
        /* UPDATE: newer versions use tt-input instead of tt-query */
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }

    .tt-hint {
        color: #999;
    }

    .tt-menu {
        /* UPDATE: newer versions use tt-menu instead of tt-dropdown-menu */
        width: 422px;
        margin-top: 12px;
        padding: 8px 0;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
    }

    .tt-suggestion {
        padding: 3px 20px;
        font-size: 18px;
        line-height: 24px;
        cursor: pointer;
    }

    .tt-suggestion:hover {
        color: #f0f0f0;
        background-color: #0097cf;
    }

    .tt-suggestion p {
        margin: 0;
    }


    input.typeahead.tt-hint , input.typeahead-substation.tt-hint {
        border: 0px !important;
        background: transparent !important;
        padding: 20px 14px;
        font-size: 15px !important;

    }
</style>
