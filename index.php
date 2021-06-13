<?php
/*
 * Written by Yonathan Tesfaye on 06/06/2021 at 9:53 AM
 */

require_once __DIR__ . "/EthiopianCalendar/EthiopianCalendar.php";
function Copyright($year): string
{
    $c = $year;
    if (date("Y", strtotime("$year-01-01")) < date('Y')) {
        $c = $year . "-" . date('Y');
    }
    return $c;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="EthiopianCalendar/EthiopianCalendar.css">
    <link rel="stylesheet" href="external assets/css/style.css">
    <title>Ethiopian Calendar | Gregorian Calendar to Ethiopian Calendar Converter</title>
</head>
<body>
<div class="nav">
    <div class="flex">
        <div>
            <h3 class="title">Ethiopian Calendar</h3>
        </div>
        <div>
            <ul>
                <li class="drop-down">
                    <a href="#" class="drop-down-btn">About</a>
                    <div class="drop-down-content">
                        <div style="display: inline">
                            <p>Thank you for using !!!</p>
                            <hr>
                            <div><b>EthiopianCalendar</b></div>
                            <div><small>Github - <a href="https://github.com/ytDevelopment-ET/EthiopianCalendar">EthiopianCalendar</a></small></div>
                        </div>
                        <hr>
                        <div style="display: inline">
                            <div><img src="external assets/img/ytDevelopment.png" alt="ytDevelopment" width="100" height="100"
                                      style="border-radius: 50%"></div>
                            <div><b>ytDevelopment</b></div>
                            <div><small>Website - <a
                                            href="http://ytdevelopment.rf.gd/">http://ytdevelopment.rf.gd</a></small>
                            </div>
                        </div>
                        <hr>
                        <b><small>&copy; <?php echo Copyright("2019"); ?> ytDevelopment</small></b>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="content">
    <div>
        Only two files are required to use the converter
            <ul>
                <li><b>EthiopianCalendar.php</b> : contains php script basis for conversion,</li>
                <li><b>EthiopianCalendar.css</b> : styles for the table.</li>
            </ul>
<pre>...
&lt;<span class="tag">link</span> <span class="attr">rel</span>=<span class="string">"stylesheet"</span> <span class="attr">href</span>=<span class="string">"...EthiopianCalendar.css"</span>&gt;
...
<span class="red">&lt;?php</span>

<span class="keyword">require_once</span> <span class="string">"...EthiopianCalendar.php"</span>;

<span class="comment">// Use the following code to get the result below</span>
<span class="variable">$EC</span> = <span class="keyword">new</span> <span class="method">EthiopianCalendar</span>(<span class="method">date</span>(<span class="string">"Y-m-d"</span>)); <span class="comment">// set current GC date</span>
<span class="comment">//$EC = new EthiopianCalendar(date("YYYY-mm-dd")); // custom date format</span>

<span class="variable">$EC</span>-&gt;<span class="method">ECDrawCalendar</span>(); <span class="comment">// draw Ethiopian Calendar table</span>
<span class="variable">$EC</span>-&gt;<span class="method">GCDrawCalendar</span>(); <span class="comment">// draw Gregorian Calendar table</span>

<span class="comment">// $EC->GetLeapYear(); // returns leap year 5 or 6</span>
<span class="comment">// $EC->GetECDate(string $format); // get Ethiopian Calendar date with custom date format</span>
<span class="comment">// $EC->GetECMonthLength(); // returns 30 for others, 5 or 6 for leap year</span>
<span class="comment">// $EC->GetGCDate(string $format); // get Gregorian Calendar date with custom date format</span>
<span class="comment">// $EC->GetGCMonthFullName(); // returns full name of Gregorian Calendar month</span>
<span class="comment">// $EC->GetGCDayFullName(); // returns full name of Gregorian Calendar day</span>
<span class="comment">// $EC->GetGCMonthLength(); // returns length off Gregorian Calendar month</span>
<span class="red">?&gt;</span></pre>
    </div>
    <?php
    $EC = new EthiopianCalendar(date("Y-m-d")); // set current GC date
    //$EC = new EthiopianCalendar(date("YYYY-mm-dd")); // custom date format

    $EC->ECDrawCalendar(); // draw Ethiopian Calendar table
    $EC->GCDrawCalendar(); // draw Gregorian Calendar table
    ?>
</div>
</body>
</html>