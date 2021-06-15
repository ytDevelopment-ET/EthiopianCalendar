# EthiopianCalendar
Gregorian Calendar to Ethiopian Calendar Converter for PHP
<br>
`<?php
    $EC = new EthiopianCalendar(date("Y-m-d")); // set current GC date
    //$EC = new EthiopianCalendar(date("YYYY-mm-dd")); // custom date format
    $EC->ECDrawCalendar(); // draw Ethiopian Calendar table
    $EC->GCDrawCalendar(); // draw Gregorian Calendar table
?>`
