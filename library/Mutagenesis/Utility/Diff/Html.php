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

class Html extends PhpUnitAbstract implements ProviderInterface
{
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

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightAdded($line)
    {
        return '<span style="background-color:#DFF0D8;">' . $this->highlight($line) . "</span>";
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightRemoved($line)
    {
        return '<span style="background-color:#F2DEDE;">' . $this->highlight($line) . "</span>";
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightContext($line)
    {
        return ' ' . $this->highlight($line);
    }

    /**
     * @param array $buffer
     *
     * @return string
     */
    protected function implodeBuffer(array $buffer)
    {
        return implode('<br />', $buffer) . '<br />';
    }

}
