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

namespace Mutagenesis\Renderer;

use Mutagenesis\Mutant\MutantInterface;

class Text extends RendererAbstract
{
    /**
     * {@inheritdoc}
     */
    public function renderReport($total, $captured, $escaped, array $mutables, $output = '')
    {
        $out = PHP_EOL . PHP_EOL
            . 'Score: '
            . $this->calculateScore($total, $escaped) . '%'
            . PHP_EOL . PHP_EOL;
        $out .= $total
            . ($total == 1 ? ' Mutant' : ' Mutants')
            . ' born out of the mutagenic slime!'
            . PHP_EOL . PHP_EOL;
        if ($escaped > 0) {
            $out .= $escaped
                . ($escaped == 1 ? ' Mutant' : ' Mutants')
                . ' escaped; the integrity of your source code may be compromised by the following Mutants:'
                . PHP_EOL . PHP_EOL;
            $i = 1;
            /** @var \Mutagenesis\Mutable $mutable */
            foreach ($mutables as $mutable) {
                /** @var MutantInterface $mutant */
                foreach ($mutable->getMutantsEscaped() as $mutant) {
                    $out .= $i . ')'
                        . PHP_EOL
                        . 'Difference on ' . $mutant->getClassName() . '::' . $mutant->getMethodName()
                        . '() in ' . $mutant->getFileName()
                        . PHP_EOL . str_repeat('=', 67) . PHP_EOL
                        . $mutant->getDiff()
                        . PHP_EOL;
                    if (!empty($output)) {
                        $out .= $this->indentTestOutput($output)
                            . PHP_EOL . PHP_EOL;
                    }
                    $i++;
                }
            }
            $out .= 'Happy Hunting! Remember that some Mutants may just be'
                . ' Ghosts (or if you want to be boring, \'false positives\').'
                . PHP_EOL . PHP_EOL;
        } else {
            $out .= 'No Mutants survived! Someone in QA will be happy.'
                . PHP_EOL . PHP_EOL;
        }
        if ($this->isDetailCaptures() && $captured > 0) {
            $out .= 'The following Mutants were safely captured (see above for escapees):'
                . PHP_EOL . PHP_EOL;
            $i = 1;
            /** @var \Mutagenesis\Mutable $mutable */
            foreach ($mutables as $mutable) {
                /** @var MutantInterface $mutant */
                foreach ($mutable->getMutantsCaptured() as $mutant) {
                    $out .= $i . ')'
                        . PHP_EOL
                        . 'Difference on ' . $mutant->getClassName() . '::' . $mutant->getMethodName()
                        . '() in ' . $mutant->getFileName()
                        . PHP_EOL . str_repeat('=', 67) . PHP_EOL
                        . $mutant->getDiff()
                        . PHP_EOL;
                    $out .= 'Reported test output:' . PHP_EOL
                        . PHP_EOL . $this->indentTestOutput($mutant->getStdError()) . PHP_EOL . PHP_EOL;
                    $i++;
                }
            }
            $out .= "Check above for the capture details to see if any mutants"
                . ' escaped.';
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function getDiffProvider()
    {
        return new \Mutagenesis\Utility\Diff\PhpUnit();
    }
}
