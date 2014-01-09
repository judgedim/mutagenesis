<?php
/**
 * Mutagenesis
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mutateme/blob/rewrite/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mutagenesis
 * @package    Mutagenesis
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mutateme/blob/rewrite/LICENSE New BSD License
 */

namespace MutagenesisTest\Diff;

use Mutagenesis\Utility\Diff\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Html
     */
    public $provider;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->provider = new Html();
    }

    public function testInstanseOfProviderInterface()
    {
        $this->assertInstanceOf('\Mutagenesis\Utility\Diff\ProviderInterface', $this->provider);
    }

    /**
     * @dataProvider differenceProvider
     */
    public function testDifference($from, $to, $expected)
    {
        $this->assertEquals($expected, $this->provider->difference($from, $to));
    }

    public function differenceProvider()
    {
        $expected1 = "<span style=\"background-color:#F2DEDE;\"><code><span style=\"color: #000000\">
<span style=\"color: #0000BB\"></span><span style=\"color: #007700\">if&nbsp;(</span><span style=\"color: #0000BB\">\$p&nbsp;</span><span style=\"color: #007700\">!==&nbsp;</span><span style=\"color: #0000BB\">0</span><span style=\"color: #007700\">)&nbsp;{</span>
</span>
</code></span><br /><span style=\"background-color:#DFF0D8;\"><code><span style=\"color: #000000\">
<span style=\"color: #0000BB\"></span><span style=\"color: #007700\">if&nbsp;(</span><span style=\"color: #0000BB\">\$p&nbsp;</span><span style=\"color: #007700\">===&nbsp;</span><span style=\"color: #0000BB\">0</span><span style=\"color: #007700\">)&nbsp;{</span>
</span>
</code></span><br /> <code><span style=\"color: #000000\">
<span style=\"color: #0000BB\">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style=\"color: #007700\">return&nbsp;</span><span style=\"color: #0000BB\">true</span><span style=\"color: #007700\">;</span>
</span>
</code><br /> <code><span style=\"color: #000000\">
<span style=\"color: #0000BB\"></span><span style=\"color: #007700\">}</span>
</span>
</code><br />";
        $from1 = <<<BLOCK1
if (\$p !== 0) {
    return true;
}
BLOCK1;

        $to1 = <<<BLOCK2
if (\$p === 0) {
    return true;
}
BLOCK2;

        $expected2 = " <code><span style=\"color: #000000\">
<span style=\"color: #0000BB\"></span>
</span>
</code><br />";

        return array(
            array(
                $from1,
                $to1,
                $expected1
            ),
            array(
                '',
                '',
                $expected2
            ),
        );
    }
}
 