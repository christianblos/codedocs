<?php
namespace CodeDocs\Processor\Internal;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use Markdown2Html\Builder;
use Markdown2Html\Theme\DefaultTheme;

/**
 * Converts exported files to html
 */
class Mardown2Html implements ProcessorInterface
{
    /**
     * @var string
     */
    private $destination;

    /**
     * @var string
     */
    private $githubLink;

    /**
     * @param string $destination
     * @param string $githubLink
     */
    public function __construct($destination, $githubLink)
    {
        $this->destination = $destination;
        $this->githubLink  = $githubLink;
    }

    /**
     * @param State  $state
     * @param Logger $logger
     */
    public function run(State $state, Logger $logger)
    {
        $filesystem = new Filesystem();

        $from = realpath($state->config->getExportDir());
        if (!$from) {
            throw new MarkupException('export dir does not exist');
        }

        if (!$this->destination) {
            throw new MarkupException('destination no set');
        }

        $to = $state->config->getBaseDir() . '/' . $this->destination;

        $filesystem->purge($to);

        $logger->log(0, 'run markdown2html');

        $theme        = new DefaultTheme();
        $theme->title = 'CodeDocs';

        $theme->naviLinks = [
            'Github' => $this->githubLink,
        ];

        $builder = new Builder();
        $builder->build($from, $this->destination, $theme);
    }
}
