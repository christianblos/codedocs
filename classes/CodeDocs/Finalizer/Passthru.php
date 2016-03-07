<?php
namespace CodeDocs\Finalizer;

use CodeDocs\ListItem;
use CodeDocs\Model\Config;
use CodeDocs\Topic;

/**
 * @Topic(file="02.usage/04.finalizers/00.Passthru/docs.md")
 *
 * ---
 * title: Passthru-Finalizer
 * taxonomy:
 *     category: docs
 * ---
 *
 * This finalizer runs a command via `passthru()`.
 *
 * ```yaml
 * finalizers:
 *   - \CodeDocs\Finalizer\Passthru:
 *       cmd: pwd
 * ```
 *
 * The command will be executed from the build dir.
 *
 * @ListItem(list="finalizers", link="/usage/finalizers/Passthru")
 */
class Passthru extends Finalizer
{

    /**
     * @param Config $config
     */
    public function run(Config $config)
    {
        $cmd = $this->getMandatoryParam('cmd');

        chdir($config->getBuildDir());

        passthru($cmd);
    }
}
