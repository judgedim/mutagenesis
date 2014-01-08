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
 * @author     Dmitry Maltsev <judgedim@gmail.com>
 */

namespace Mutagenesis\Renderer;

class Html extends RendererAbstract
{
    /**
     * {@inheritdoc}
     */
    public function renderReport($total, $captured, $escaped, array $mutables, $output = '')
    {
        $byFile = array();

        /** @var \Mutagenesis\Mutable $mutable */
        foreach ($mutables as $mutable) {
            $score = $this->calculateScore($mutable->getMutants()->count(), $mutable->getMutantsEscaped()->count());
            $byFile[$mutable->getFilename()] = array(
                'score' => $score,
                'scoreStep' => ceil($score / 25) * 25,
                'escaped' => $mutable->getMutantsEscaped()->count(),
                'mutants' => $mutable->getMutants()->count(),
                'mutantsEscaped' => $mutable->getMutantsEscaped(),
                'mutantsCaptured' => $this->isDetailCaptures() ? $mutable->getMutantsCaptured() : array()
            );
        }

        // render html
        $html = $this->getTwig()->render('report.html.twig', array(
            'files' => $byFile,
            'total' => $total,
            'escaped' => $escaped,
            'score' => $this->calculateScore($total, $escaped)
        ));

        $this->writeLog($html);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiffProvider()
    {
        return new \Mutagenesis\Utility\Diff\Html();
    }

    /**
     * @param string $html
     */
    protected function writeLog($html)
    {
        $filename = $this->getLogPath() . DIRECTORY_SEPARATOR . 'mutation.html';
        file_put_contents($filename, $html);
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/views/');
        return new \Twig_Environment($loader, array());
    }
}
 