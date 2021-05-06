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
        $parsedEntries = array();
        foreach ($entries as $entry) {
            if ($entry->isDirectory()) {
                continue;
            }

            $content = $zipFile->getEntryContents($entry->getName());
            $timestamp = null;
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

            // 150000 bytes allow for ~38000 values worth ~1 minute of ecg
            $maxMemSize = 300000;
            $valuesEndIndex = $contentLen - 2;

            // for now work on the first chunk only
            $valuesContent = substr($content, $valuesStartIndex, $maxMemSize);
            $valuesArray = explode(',', $valuesContent);

            // cut the first second off
            $msToSkip = 1000;
            $valuesArray = array_slice($valuesArray, $msToSkip);
            $timestamp += $msToSkip;

            $pulseArray = [];
            $parsedValues = [];

            $eventsP = [];
            $eventsB = [];
            $eventsT = [];
            $eventsAF = [];

            // remove last element as it may be incorrectly cut off
            array_pop($valuesArray);
            foreach ($valuesArray as $ms => $value) {

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
                            array_push($eventsP, $ms);

                        // bradycardia
                        if (is_numeric(strpos($value, 'B')))
                            array_push($eventsB, $ms);

                        // tachycardia
                        if (is_numeric(strpos($value, 'T')))
                            array_push($eventsT, $ms);

                        // atrial fibrillation
                        if (is_numeric(strpos($value, 'AF')))
                            array_push($eventsAF, $ms);

                        array_push($parsedValues, $split[1]);
                    }
                    // normal values
                    else if (count($split) == 1) {
                        array_push($parsedValues, $value);
                    }
                }
            }

            array_push($parsedEntries, [
                'timestamp' => $timestamp,
                'pulse' => $pulseArray,
                'eventsP' => $eventsP,
                'eventsB' => $eventsB,
                'eventsT' => $eventsT,
                'eventsAF' => $eventsAF,
                'values' => $parsedValues,
            ]);
        }
        
        $zipFile->close();

        return $parsedEntries;
    }
}
