# EthiopianCalendar
Gregorian Calendar to Ethiopian Calendar Converter for PHP
<br>
<br>

> Steps to run the demo

1. Extract the files on your server's root directory
2. Go to your browser and navigate to **index.php**
3. You will get current day in GC & EC format

> Use the following code to convert GC to EC

`<?php`
<br>`$EC = new EthiopianCalendar(date("Y-m-d")); // set current GC date`
<br>`//$EC = new EthiopianCalendar(date("YYYY-mm-dd")); // custom date format`
<br>`$EC->ECDrawCalendar(); // draw Ethiopian Calendar table`
<br>`$EC->GCDrawCalendar(); // draw Gregorian Calendar table`
<br>`?>`

# Required Files

Only two files are required to use the converter
> EthiopianCalendar.php
> EthiopianCalendar.css
