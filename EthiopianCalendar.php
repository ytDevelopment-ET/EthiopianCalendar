<?php
/*
 * Ethiopian Calendar v1.0 : https://github.com/ytDevelopment-ET/EthiopianCalendar
 *
 * Copyright (©) 2019-2020 ytDevelopment
 * http://ytdevelopment.rf.gd/
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.

 * Written by Yonathan Tesfaye on 06/05/2021 at 2:16 AM
 * Contact Me : yt040448@gmail.com
 *
 * Gregorian Calendar(GC) to Ethiopian Calendar(EC) Converter for PHP
 * Created & tested under PHP 7.4
 *
 * ALL THE ABOVE COPYRIGHT NOTICE SHALL BE INCLUDED IN ALL COPIES OR SUBSTANTIAL PORTIONS OF THE SOFTWARE.
 *
 */

class EthiopianCalendar
{
    private int $year; // GC year
    private int $month; // GC month
    private int $day; // GC day
    private int $leap_year;

    private int $EC_year;
    private int $EC_month;
    private int $EC_day;

    private int $GCDate;

    const FIVE = 5; // leap year five
    const SIX = 6; // leap year six

    private const GregorianMonthLength = array(31, array(true => 29, false => 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    private const EthiopianMonth = array(5, 6, 7, 8, 9, 10, 11, 12, 1, 2, 3, 4);

    private const GregorianMonthName = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    private const EthiopianMonthName = array("መስከረም", "ጥቅምት", "ህዳር", "ታህሳስ", "ጥር", "የካቲት", "መጋቢት", "ሚያዚያ", "ግንቦት", "ሰኔ", "ሐምሌ", "ነሀሴ", "ጳጉሜ");

    private const GregorianDayName = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    private const EthiopianDayName = array("ሰኞ", "ማክሰኞ", "ረቡዕ", "ሐሙስ", "ዓርብ", "ቅዳሜ", "እሁድ");

    // give negative number for each week days for gaping the table row
    private const WEEK_DAY_LIST = array("Monday" => 0, "Tuesday" => -1, "Wednesday" => -2, "Thursday" => -3, "Friday" => -4, "Saturday" => -5, "Sunday" => -6);

    // day difference of each month 5 =>... & 6 =>... are day difference in leap year 5 & 6 only affects months from 9(Sep) - 12(Dec)
    private const MonthDifference = array(8, 7, 9, 8, 8, 7, 7, 6, array(5 => 5, 6 => 6), array(5 => 10, 6 => 11), array(5 => 9, 6 => 10), array(5 => 9, 6 => 10),);
    private bool $Converted = false; // make conversion is not succeeded

    public function __construct($date)
    {
        // parse the given date from string
        $str_time = strtotime($date);

        $this->year = date('Y', $str_time); // set GC year from parsed date
        $this->month = date('m', $str_time); // set GC month from parsed date
        $this->day = date('d', $str_time); // set GC day from parsed date

        if ($this->year > 0 && $this->month > 0 && $this->month <= 12 && $this->day > 0 && $this->day <= $this->GCMonthLength()) { // validate GC month & day length
            $this->SetLeapYear(); // set leap year 5 or 6

            $EC_year = $this->year - $this->YearDifference(); // set EC year from given GC year by subtraction
            $EC_month = self::EthiopianMonth[$this->month - 1]; // set EC month from its array (-1 for array index)

            // !there is a change in day difference because of leap year (5 & 6) for Sep-Dec
            if ($this->month >= 9 && $this->month <= 12) { // check the month whether it is from 9 (Sep) upto 12 (Dec)
                if ($this->month == 9) { // check the month is 9 (Sep)
                    $EC_day = $this->day - (self::MonthDifference[$this->month - 1][$this->leap_year] + $this->leap_year + $this->LeapYearAddition()); // set EC date by handling & adding day difference in leap year 5 & 6
                    if ($EC_day >= -5 && $EC_day <= 0) { // check day for decreasing the month from Sep to leap year
                        $EC_day += 6; // for leap year day
                        $EC_month = 13; // for leap year month
                    } elseif ($EC_day < -5) { // out of leap year
                        $EC_day += 6;
                    }
                } else {
                    $EC_day = $this->day - (self::MonthDifference[$this->month - 1][$this->leap_year]); // set EC date by handling day difference in leap year 5 & 6
                }
            } else {
                $EC_day = $this->day - self::MonthDifference[$this->month - 1]; // set EC day from GC day difference subtraction
            }

            if ($EC_day <= 0) { // check negativity
                $EC_day = 30 + $EC_day; // remove negativity
                $EC_month = self::EthiopianMonth[$this->month - 2] ?? self::EthiopianMonth[($this->month - 2) + count(self::EthiopianMonth)]; // decrease month because of day negativity
            }

            $this->EC_year = $EC_year; // set EC year
            $this->EC_month = $EC_month; // set EC month
            $this->EC_day = $EC_day; // set EC day

            $this->Converted = true; // ensure conversion

            $this->GCDate = strtotime("$this->year-$this->month-$this->day"); // set GC date
        }
    }

    private function SetLeapYear()
    {
        // fromula for getting leap year 5 or 6 from GC year
        $leap_year_division = ($this->year - 11) / 4;

        if (is_int($leap_year_division)) {
            $this->leap_year = self::SIX;
        } else {
            $this->leap_year = self::FIVE;
        }
    }

    private function YearDifference(): int
    {
        // handle day differences from 9 (Sep) - 12 (Dec) with leap year changes
        if ($this->month == 9) {
            if (($this->day >= 11 && $this->leap_year == self::FIVE) || ($this->day >= 12 && $this->leap_year == self::SIX)) {
                return 7;
            } else {
                return 8;
            }
        } elseif ($this->month > 9) {
            return 7;
        } else {
            return 8;
        }
    }

    private function LeapYearAddition(): int
    {
        // get leap year addition for month 9 (Sep)
        if ($this->leap_year == self::FIVE) {
            return +1;
        } elseif ($this->leap_year == self::SIX) {
            return -1;
        } else {
            return 1;
        }
    }

    private function AddZero($int): string
    {
        // add first zero for year & month Eg, 1 becomes 01
        if (strlen($int) == 1) {
            return "0$int";
        } else {
            return $int;
        }
    }

    private function EC_date_format($format)
    {
        // format EC date like PHP builtin function date('Y-m-d...')
        // see GetECDate() for detail
        if ($this->Converted) {
            $format = str_replace("Y", $this->EC_year, $format);
            $format = str_replace("y", substr($this->EC_year, 2, 2), $format);

            $format = str_replace("M", self::EthiopianMonthName[$this->EC_month - 1], $format);
            $format = str_replace("m", $this->AddZero($this->EC_month), $format);

            $format = str_replace("d", $this->AddZero($this->EC_day), $format);

            $GC_day_name = $this->GetGCDate('D');
            $day = 0;
            foreach (self::GregorianDayName as $EC_day_full_name) {
                if ($GC_day_name == substr($EC_day_full_name, 0, 3)) {
                    $format = str_replace("D", self::EthiopianDayName[$day], $format);
                    break;
                }
                $day++;
            }

            return $format;
        } else {
            return "";
        }
    }

    private function GCMonthLength(): int
    {
        // calculate GC month length
        if ($this->month == 2) {
            return self::GregorianMonthLength[$this->month - 1][is_int($this->year / 4)];
        } else {
            return self::GregorianMonthLength[$this->month - 1];
        }
    }

    private function MatchDay($count_day, $today_int, $return_true, $return_false = ""): string
    {
        // match today date with loop counting day, for marking today with color
        if ($count_day == $today_int) {
            return $return_true;
        } else {
            return $return_false;
        }
    }

    public function GetLeapYear()
    {
        // returns leap year
        if ($this->Converted) {
            return $this->leap_year;
        } else {
            return "";
        }
    }

    public function GetECDate($format): string
    {
        // get EC date with letter
        // Y:- four digit year y:- two digit
        // M:- month name m:- month number
        // D:- day name d:- day number
        if ($this->Converted) {
            return $this->EC_date_format($format);
        } else {
            return "";
        }
    }

    public function GetECMonthLength(): int
    {
        // get EC month length including leap year
        if ($this->Converted) {
            if ($this->EC_month == 13) {
                return $this->leap_year;
            } else {
                return 30;
            }
        } else {
            return 0;
        }
    }

    public function GetGCDate($format): string
    {
        // get GC date with letter, it uses date() function
        if ($this->Converted) {
            return date($format, $this->GCDate); // here date()
        } else {
            return "";
        }
    }

    public function GetGCMonthFullName(): string
    {
        // get GC month full name, because of three letters only in date('M')
        if ($this->Converted) {
            $month = "";
            $GC_month_name = $this->GetGCDate('M');
            foreach (self::GregorianMonthName as $GC_month_full_name) {
                if ($GC_month_name == substr($GC_month_full_name, 0, 3)) {
                    $month = $GC_month_full_name;
                    break;
                }
            }

            return $month;
        } else {
            return "";
        }
    }

    public function GetGCDayFullName(): string
    {
        // get GC day full name, because of three letters only in date('D')
        if ($this->Converted) {
            $day = "";
            $GC_day_name = $this->GetGCDate('D');
            foreach (self::GregorianDayName as $GC_day_full_name) {
                if ($GC_day_name == substr($GC_day_full_name, 0, 3)) {
                    $day = $GC_day_full_name;
                    break;
                }
            }

            return $day;
        } else {
            return "";
        }
    }

    public function GetGCMonthLength(): int
    {
        // returns length of the month of GC
        if ($this->Converted) {
            return $this->GCMonthLength();
        } else {
            return 0;
        }
    }

    // the both EC & GC calendar display is created in the same way using table plus loop
    // in the loop, counting all dates until the month's max length is reached & match today to give color
    // use negative number for gaping until the current day column
    // negative number is not displayed because the loop works between 1 upto max month length until it reaches 1 gaps are created

    public function ECDrawCalendar()
    {
        if ($this->Converted) {
            echo "<table class='calendar'><tr><th>ሰኞ</th><th>ማክሰኞ</th><th>ረቡዕ</th><th>ሐሙስ</th><th>ዓርብ</th><th class='rest'>ቅዳሜ</th><th class='rest'>እሁድ</th></tr>";

            $date = $this->EC_day;
            while ($date > 7) {
                $date -= 7;
            }

            $date = -(((-self::WEEK_DAY_LIST[$this->GetGCDayFullName()]) + 1) - $date);
            if ($date > 0) {
                $date = $date - 7;
            }

            $count_day = $date;
            while ($count_day < $this->GetECMonthLength()) {
                if ($date >= -6 && $date <= 0) {
                    $count_day++;
                    if ($count_day > 0) {
                        if (is_int(($count_day - (7 + $date)) / 7)) {
                            echo "<td class='" . $this->MatchDay($count_day, $this->GetECDate('d'), 'today', 'day') . "'>" . $count_day . "</td><tr>";
                        } else {
                            echo "<td class='" . $this->MatchDay($count_day, $this->GetECDate('d'), 'today', 'day') . "'>" . $count_day . "</td>";
                        }
                    } else {
                        echo "<td></td>";
                    }
                } else {
                    break;
                }
            }
            echo "</tr><tr><td class='today' colspan='7'>" . $this->GetECDate('Y-m-d / D M d Yዓ.ም') . "</td></tr></table>";
        }
    }

    public function GCDrawCalendar()
    {
        if ($this->Converted) {
            echo "<table class='calendar'><tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th class='rest'>Sun</th></tr>";

            $date = $this->day;
            while ($date > 7) {
                $date -= 7;
            }

            $date = -(((-self::WEEK_DAY_LIST[$this->GetGCDayFullName()]) + 1) - $date);
            if ($date > 0) {
                $date = $date - 7;
            }

            $count_day = $date;
            while ($count_day < $this->GetGCMonthLength()) {
                if ($date >= -6 && $date <= 0) {
                    $count_day++;
                    if ($count_day > 0) {
                        if (is_int(($count_day - (7 + $date)) / 7)) {
                            echo "<td class='" . $this->MatchDay($count_day, $this->GetGCDate('d'), 'today', 'day') . "'>" . $count_day . "</td><tr>";
                        } else {
                            echo "<td class='" . $this->MatchDay($count_day, $this->GetGCDate('d'), 'today', 'day') . "'>" . $count_day . "</td>";
                        }
                    } else {
                        echo "<td></td>";
                    }
                } else {
                    break;
                }
            }
            echo "</tr><tr><td class='today' colspan='7'>" . $this->GetGCDate('Y-m-d / ') . $this->GetGCDayFullName() . " " . $this->GetGCMonthFullName() . $this->GetGCDate(" d Y") . "</td></tr></table>";
        }
    }
}