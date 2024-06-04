<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</head>
<style>
    .container {
        display: grid;
        align-items: center;
        align-content: center;
        justify-items: center;
        height: 90vh;
    }

    .container img {
        animation: slideIn 2s forwards;
    }


    .container .btn {
        animation: btnSlide 1s forwards;
        transition: transform 0.3s;
        border-radius: 100px;
    }

    .container .btn:hover {
        transform: scale(1.1);
    }

    @keyframes slideIn {
        0% {
            transform: translateY(-20%);
        }

        100% {
            transform: translateY(0);
        }

    }

    @keyframes btnSlide {
        0% {

            transform: translateX(-30%);
        }

        100% {

            transform: translateX(0%);
        }
    }

    @media only screen and (max-width: 445px) {
        img {
            height: 70px !important;
        }
    }
</style>

<body>
    <div class="container">
        <img src="{{ URL::asset('assets/web-images/main-logo.png') }}" alt="" height="265">
        <a href="{{app()->getLocale()}}/dashboard">
            <button class="btn  btn-success mt-4"> GOTO MAIN</button>
        </a>
    </div>
</body>

</html>
