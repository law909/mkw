<?php

namespace Services;

/** ECB euro reference rates; the OSS return converts at them, not at the MNB rates of the invoices. */
class EkbArfolyamService
{
    private const URL = 'https://data-api.ecb.europa.eu/service/data/EXR/D.%s.EUR.SP00.A?startPeriod=%s&endPeriod=%s&format=csvdata';

    /**
     * 1 EUR in $valuta on $datum (Y-m-d), or on the next day with a published rate: there is none on weekends and
     * holidays. Null when the ECB does not answer, does not quote the currency or has not published it yet.
     *
     * @return array{datum: string, arfolyam: float}|null
     */
    public function getRate(string $valuta, string $datum): ?array
    {
        if ($valuta === 'EUR') {
            return ['datum' => $datum, 'arfolyam' => 1.0];
        }
        if (!preg_match('/^[A-Z]{3}$/', $valuta)) {
            return null;
        }
        $url = sprintf(self::URL, $valuta, $datum, date('Y-m-d', strtotime($datum . ' +10 days')));
        // a connection that could not be made (HTTP 0) is tried once more
        for ($proba = 0; $proba < 2; $proba++) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15, CURLOPT_FOLLOWLOCATION => true]);
            $csv = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $hiba = curl_error($ch);
            curl_close($ch);
            if ($status !== 0) {
                break;
            }
        }
        if ($csv === false || $status !== 200) {
            \mkw\store::writelog('EKB árfolyam ' . $valuta . ' ' . $datum . ': HTTP ' . $status . ' ' . $hiba, 'ekbarfolyam.txt');
            return null;
        }
        $lines = array_values(array_filter(explode("\n", trim($csv))));
        $header = str_getcsv(array_shift($lines) ?? '');
        $datumOszlop = array_search('TIME_PERIOD', $header, true);
        $ertekOszlop = array_search('OBS_VALUE', $header, true);
        if ($datumOszlop === false || $ertekOszlop === false) {
            return null;
        }
        $rates = [];
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (($row[$ertekOszlop] ?? '') !== '') {
                $rates[$row[$datumOszlop]] = (float)$row[$ertekOszlop];
            }
        }
        ksort($rates);
        foreach ($rates as $nap => $arfolyam) {
            if ($nap >= $datum) {
                return ['datum' => $nap, 'arfolyam' => $arfolyam];
            }
        }
        return null;
    }
}
