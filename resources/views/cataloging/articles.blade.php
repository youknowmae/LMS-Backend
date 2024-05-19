<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/cataloging/reports.css') }}">

        {{-- FONT IMPORT  --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <title>LIMS Cataloging</title>

        <style>
            body {
                font-family: "Montserrat", sans-serif;
                font-size: 12px;
            }

            .normal-font, .bold-font, .small-font {
                text-align: center;
                margin: 5px;
            }

            .bold-font {
                font-weight: 800;
            }

            .small-font {
                font-size: 10px;
            }

            #library-logo {
                height: 120px;
                position: absolute;
                top: 10px;
                right: 70px;
            }

            #gc-logo {
                height: 120px;
                position: absolute;
                top: 10px;
                left: 70px;
            }

            /* TABLE STYLES */
            .table {
                margin: auto;
                border-collapse: collapse;
            }

            .table th {
                border: 1px solid black;
                padding: 10px;
            } 
            
            .table td {
                border: 1px solid black;
                padding: 5px;
            }

            .table th {
                background-color: #31A463;
                color: white;
            }

            .center-txt {
                text-align: center;
            }

            .long-td {
                width: 300px;
            }

            .medium-td {
                width: 200px;
            }

            .short-td {
                width: 100px;
            }
        </style>
    </head>

    <body>
        <header class="header">
            <img id="library-logo" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage\cataloging assets\GC LIBRARY.png'))) }}">
            <img id="gc-logo" src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('storage\cataloging assets\GC.png'))) }}">
            <div class="normal-font"> Republic of the Philippines  </div>
            <div class="normal-font"> City of Olongapo </div>
            <div class="bold-font"> GORDON COLLEGE </div>
            <div class="small-font"> Olongapo City Sports Complex, Donor St., East Tapinac, Olongapo City </div>
            <div class="small-font"> Tel. No.: (047) 224-2089 loc. 401 </div>
            <br>

            <div class="bold-font"> LIBRARY AND INSTRUCTIONAL MEDIA CENTER </div>
        </header><br>

        <table class="table">
            <thead>
                <th> Article<br>Accession </th>
                <th> Title </th>
                <th> Authors </th>
                <th> Publication Date </th>
            </thead>

            <tbody>
                @foreach ($materials as $material)
                    <tr>
                        <td class="short-td center-txt"> {{ $material->id }} </td>
                        <td class="long-td"> {{ $material->title }} </td>
                        <td class="medium-td"> 
                            @foreach ($material->authors as $index => $author)
                                {{ $author }}@if($index < count($material->authors) - 1),<br> @endif
                            @endforeach 
                        </td>
                        <td class="medium-td center-txt"> {{ date('M d, Y', strtotime($material->date_published)) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
