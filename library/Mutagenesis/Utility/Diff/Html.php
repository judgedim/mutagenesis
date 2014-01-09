<?php

/**
 * Mutagenesis
 *
 * LICENSE
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * @category   Mutagenesis
 * @package    Mutagenesis
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2013 Jean-François Lépine <http://blog.lepine.pro>
 * @license    https://github.com/Halleck45/MutaTesting/blob/master/LICENCE
 * @author     Jean-François Lépine (https://github.com/Halleck45)
 */

namespace Mutagenesis\Utility\Diff;

class Html implements ProviderInterface
{
    /**
     * Returns the diff between two arrays or strings as string.
     *
     * @param array|string $from
     * @param array|string $to
     * @param int          $contextLines
     *
     * @return string
     */
    public function difference($from, $to, $contextLines = 3)
    {
        $tool   = new \SebastianBergmann\Diff\Differ('');
        $buffer = '';

        $diff = $tool->diffToArray($from, $to);

        $inOld = false;
        $i     = 0;
        $old   = array();

        foreach ($diff as $line) {
            if ($line[1] === 0 /* OLD */) {
                if ($inOld === false) {
                    $inOld = $i;
                }
            } else if ($inOld !== false) {
                if (($i - $inOld) > 5) {
                    $old[$inOld] = $i - 1;
                }

                $inOld = false;
            }
            ++$i;
        }

        $start = isset($old[0]) ? $old[0] : 0;
        $end   = count($diff);

        if ($tmp = array_search($end, $old)) {
            $end = $tmp;
        }

        $newChunk = true;

        for ($i = $start; $i < $end; $i++) {
            if (isset($old[$i])) {
                $buffer .= "<br />";
                $newChunk = true;
                $i        = $old[$i];
            }

            if ($newChunk) {
                $newChunk = false;
            }

            if ($diff[$i][1] === 1 /* ADDED */) {
                $buffer .= '<span style="background-color:#DFF0D8;">' . $this->highlight($diff[$i][0]) . "</span><br />";
            } else if ($diff[$i][1] === 2 /* REMOVED */) {
                $buffer .= '<span style="background-color:#F2DEDE;">' . $this->highlight($diff[$i][0]) . "</span><br />";
            } else {
                $buffer .= ' ' . $this->highlight($diff[$i][0]) . "<br />";
            }
        }

        return $buffer;
    }

    /**
     * @param string $string
     *
     * @return mixed
     */
    private function highlight($string)
    {
        $output = highlight_string('<?php ' . $string, true);
        $output = preg_replace('!(&lt;\?php(&nbsp;).*?)!', '', $output);

        return $output;
    }

}
