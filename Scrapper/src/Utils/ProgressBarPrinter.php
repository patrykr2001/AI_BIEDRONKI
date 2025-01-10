<?php

namespace App\Utils;

class ProgressBarPrinter
{
    public static function printProgressBar($done, $total, $size = 30, $step = 1): void
    {
        if ($done > $total) {
            return;
        }

        if ($done % $step !== 0) {
            return;
        }

        $perc = (double)($done / $total);
        $bar = floor($perc * $size);

        $status_bar = "\r[";
        $status_bar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $status_bar .= ">";
            $status_bar .= str_repeat(" ", $size - $bar);
        } else {
            $status_bar .= "=";
        }

        $disp = number_format($perc * 100, 0);

        $status_bar .= "] $disp%  $done/$total";

        echo "$status_bar  ";

        flush();

        if ($done == $total) {
            echo "\n";
        }
    }
}