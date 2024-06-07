<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{public_path('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    <title>@yield('title', 'Document')</title>
    <style>
        header {
            position: fixed;
            display: block !important;
            float: right;
            top: -20px;
            width: 100% !important;
            left: 0px;
            height: 50px;
            text-align: right;
        }
        .text-upper
        {
            text-transform: uppercase;
        }
        .text-judul2
        {
            font-size: 12pt;
            font-weight: 600; 
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif;
            clear: both;
        }
        .text-judul3
        {
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .text-right
        {
            font-family: Arial, Helvetica, sans-serif;
            text-align: right;
        }
        .text-center
        {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
        }
        .text-left
        {
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }
        .text-thead
        {
            font-size: 8pt;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            text-transform: uppercase;
        }
        .text-td
        {
            font-size: 8pt;
            font-family: Arial, Helvetica, sans-serif;
            text-transform: uppercase;
        }
        
        .table-pdf {
            border: 1px solid;
            padding-left: 5px;
            padding-right: 5px;
        }
        .text-pdf {
            font-size: 10pt;
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif;
        }
        .text-10 {
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .text-12 {
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .text-8 {
            font-size: 8pt;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        .column-pdf {
            float: left;
            width: 50%;
        }
        .row-pdf:after {
            content: "";
            display: table;
            clear: both;
        }
        .column-4 {
            float: left;
            width: 25%;
        }
    </style>
</head>
<body>
    <br>
<div class="container-fluid">@yield('content')</div>
</body>
</html>