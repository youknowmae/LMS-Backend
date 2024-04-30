<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LIMS Cataloging</title>
    </head>

    <body>
        <h1>Library Information Management System</h1>
        <h2>Cataloging Subsystem</h2>
        <hr><br>

        <h3>Material Reports: </h3>
        <p>All Books: {{ $counts['titles'] }}</p>
        <p>All Volumes: {{ $counts['volumes']}}</p>
        <p>All Magazines: {{ $counts['magazines'] }}</p>
        <p>All Journals: {{ $counts['journals'] }}</p>
        <p>All Newspapers: {{ $counts['newspapers'] }}</p>
        <p>All Articles: {{ $counts['articles'] }}</p>
        <hr><br>

        <h3>Academic Materials: </h3>
        <p>CCS: {{ $counts['ccs'] }}</p>
        <p>CAHS: {{ $counts['cahs'] }}</p>
        <p>CEAS: {{ $counts['ceas'] }}</p>
        <p>CBA: {{ $counts['cba'] }}</p>
        <p>CHTM: {{ $counts['chtm'] }}</p>
    </body>
</html>
