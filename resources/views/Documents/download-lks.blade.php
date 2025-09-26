<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>generate lks</title>
</head>

<body>

    <p  >Total Number of downloads is <span class="counts"></span></p>
    <p  >Total download complete <span id="download-complete"></span> / <span class="counts"></span></p>
    <p id='handle-request'></p>

    <p id="closing-window"></p>

    <form action="/{{app()->getLocale()}}/create-zip-lks-and-download" method="post" class="d-none" id="download-form">
    @csrf
        <input type="hidden" name="name" id="" value="{{$url}}">
        <input type="hidden" name="fileName" id="fileName" >
        <input type="hidden" name="ba" id="ba" value="{{$ba}}">
        <input type="hidden" name="folder_name" id="folder_name">
        <input type="hidden" name="from_date" id="from_date" value="{{$from_date}}">
        <input type="hidden" name="cycle" id="cycle" value="{{$cycle}}">


        <input type="hidden" name="to_date" id="to_date" value="{{$to_date}}">
    </form>



    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script>
        var ba = "{{ $ba }}";
        var from_date = "{{$from_date}}";
        var to_date = "{{$to_date}}";
        var pdfPaths = [];
        var folderName = '';
        var cycle = $('#cycle').val();
        var workPackage = '';

            workPackage = "{{isset($workPackage) ?$workPackage : '' }}";

        console.log(workPackage);

           var myurl= `/{{app()->getLocale()}}/generate-{{$url}}-lks?ba=${encodeURIComponent(ba)}&from_date=${from_date}&to_date=${to_date}&cycle=${cycle}&workPackages=${workPackage}`;
           $.ajax(
                {
                    url:myurl,
                    method: 'GET',
                    success: function(response)
                    {


                        $('.counts').html(response.visit_dates.length +1)


                        if (response.pdfPath)
                        {
                            pdfPaths.push(response.pdfPath);
                            folderName = response.folder_name;
                        }

                        generateFiles(response.visit_dates , 0);
                    },
                    error: function(error)
                    {
                        console.error('Error:', error);
                    }
                });



        function generateFiles(dates, index)
        {

            $('#handle-request').html('Please wait sending request ...');
            $('#download-complete').html(index + 1)
            if (index < dates.length)
            {
                  var url='/{{app()->getLocale()}}/generate-{{$url}}-lks-by-visit-date?ba=' + encodeURIComponent(ba) + '&visit_date=' + dates[index]+'&folder_name='+folderName+'&cycle='+cycle+'&workPackages='+workPackage;
                console.log(url)
                  $.ajax(
                    {
                        url: url,
                        method: 'GET',
                        success: function(response)
                        {
                            // Handle the success response
                            $('#handle-request').val('generating ...' + index+1 + ' / ' + dates.length);

                            if (response.pdfPath)
                            {
                                pdfPaths.push(response.pdfPath);
                            }

                            generateFiles(dates, index + 1);
                        },
                        error: function(error)
                        {
                            alert("Request failed.....");
                            // window.close()
                            console.error('Error:', error);
                        }
                    });
            }
            else
            {
                $('#handle-request').html('Files Generated Complete Please wait for download....');
                downloadGeneratedFiles();
                // window.close()
            }

        }



        function downloadGeneratedFiles()
        {
            // on top create form and set values and submit through javascript

            $('#fileName').val(pdfPaths);
            $('#folder_name').val(folderName);
            $('#download-form').submit();


            // setTimeout(() => {
            //     window.close()
            // }, 2000);



        }

        function removeFiles(pdfPath ,dates ,index)
        {
            $.ajax(
                {
                    url: '/{{app()->getLocale()}}/remove-generate-lks-by-visit-date?fileName='+pdfPath+'&folder_name='+folderName,
                    method: 'GET',
                    success: function(response)
                    {
                        generateFiles(dates, index + 1);
                    },
                    error: function(error)
                    {
                        console.error('Error:', error);
                    }
                });

        }


    </script>
</body>

</html>
