<?php

namespace App\Utils;

use Carbon\Carbon;

class Parser
{
    function parse(String $path)
    {
        $zipFile = new \PhpZip\ZipFile();

        $file = $zipFile->openFile($path);

        $entries = $file->getEntries();
        foreach ($entries as $entry) {
            if ($entry->isDirectory()) {
                continue;
            }

            $content = $zipFile->getEntryContents($entry->getName());
            $timestamp = null;
            $date = null;
            $valuesStartIndex = null;

            $contentLen = strlen($content);
            for ($i = 0; $i < $contentLen; $i++) {

                // extract timestamp
                if ($content[$i] == 't' && ($content[$i] . $content[$i + 1] == 'ts')) {

                    $timestampArray = [];
                    for ($j = $i; $content[$j] != ','; $j++) {
                        if (is_numeric($content[$j])) {
                            array_push($timestampArray, $content[$j]);
                        }
                    }

                    $timestamp = implode("", $timestampArray);
                    $date = Carbon::createFromTimestampMs($timestamp);
                }

                // find the beggining of values
                if ($content[$i] == 'v') {
                    if ($content[$i] . $content[$i + 1] . $content[$i + 2] . $content[$i + 3] . $content[$i + 4] . $content[$i + 5] == 'values') {
                        for ($j = $i; $content[$j] != '['; $j++) {
                            $valuesStartIndex = $j + 2;
                        }
                        break;
                    }
                }
            }

            // cut the values up into chunks so the memory si not overflowed

            // 300000 bytes allow for ~64000 values worth ~1 minute of ecg
            $maxMemSize = 300000;

            $valuesEndIndex = $contentLen - 2;

            // for now work on the first chunk only
            $valuesContent = substr($content, $valuesStartIndex, $maxMemSize);
            $valuesArray = explode(',', $valuesContent);

            $eventsPCount = 0;
            $eventsBCount = 0;
            $eventsTCount = 0;
            $eventsAFCount = 0;
            $pulseArray = [];
            $parsedValues = [];

            // remove last element as it may be incorrectly cut off
            array_pop($valuesArray);
            foreach ($valuesArray as $value) {

                // pulse values
                if (is_numeric(strpos($value, 'PE_'))) {
                    $pulseValue = explode('_', $value)[1];
                    array_push($pulseArray, $pulseValue);
                } else {
                    $split = explode('_', $value);

                    // abnormalities present
                    if (count($split) == 2) {
                        // pause event
                        if (is_numeric(strpos($value, 'P')))
                            $eventsPCount++;

                        // bradycardia
                        if (is_numeric(strpos($value, 'B')))
                            $eventsBCount++;

                        // tachycardia
                        if (is_numeric(strpos($value, 'T')))
                            $eventsTCount++;

                        // atrial fibrillation
                        if (is_numeric(strpos($value, 'AF')))
                            $eventsAFCount++;

                        array_push($parsedValues, $split[1]);
                    }
                    // normal values
                    else if (count($split) == 1) {
                        array_push($parsedValues, $value);
                    }
                }
            }

            return [
                'date' => $date,
                'pulse' => $pulseArray,
                'pauseEvent' => $eventsPCount,
                'bradycardia' => $eventsBCount,
                'tachycardia' => $eventsTCount,
                'atrialFibrillation' => $eventsAFCount,
                'values' => $parsedValues,
            ];
        }


        $zipFile->close();
    }
}
