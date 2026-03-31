<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Document')</title>

    <style>
        @page { margin: 10mm 30mm 20mm 30mm; }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: 80px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 100px; */
        }

        td {
            padding: 1px 2px;
            vertical-align: top;
            line-height: 1.2;
        }


        .table-border td, .table-border th {
            border: 1px solid black;
            padding: 5px;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }

        .text-td {
            font-size: 10pt;
        }

        .bold { font-weight: bold; }

        .tr{
            padding-bottom: 0px;
            padding-top: 0px;
        }

        .ttd {
            margin-top: 40px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="header">
        @stack('header')
    </div>
    <br>
<div class="container-fluid">@yield('content')</div>
</body>
</html>
