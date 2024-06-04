<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PDF Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include any CSS styles or Bootstrap styles here -->
  
</head>
<body>
    @php
        $preDate ='';   
    @endphp
    @foreach ($data as $item)
   
    @if ($preDate != $item->visit_date)
        

    @php
        $date = new DateTime($item->visit_date);
        $formattedDate = $date->format("d F Y");    
    @endphp
    <h2 class="text-center">RONDAAN {{$formattedDate}}</h2>

    @endif
    @php($preDate = $item->visit_date)


    <div class="row" style="margin: 0; padding: 0;">
        <div class="col-6" style="border: 1px solid #dee2e6; padding: 10px;">asdasdas</div>
        <div class="col-6" style="border: 1px solid #dee2e6; padding: 10px;">sdbbds</div>
        <div class="col-6" style="border: 1px solid #dee2e6; padding: 10px;">iusdfiusadi</div>
    </div>
    
    @endforeach
        
</body>
</html>
