<?php
namespace CodeDocs\Finalizer;

use CodeDocs\Component\Filesystem;
use CodeDocs\ListItem;
use CodeDocs\Model\Config;
use CodeDocs\Topic;
use CodeDocs\ValueObject\Directory;

/**
 * @Topic(file="02.usage/05.finalizers/00.CopyExportFiles/docs.md")
 *
 * ---
 * title: CopyExportFiles-Finalizer
 * taxonomy:
 *     category: docs
 * ---
 *
 * This finalizer copies all exported files to the configured destination.
 *
 * ```yaml
 * finalizers:
 *   - \CodeDocs\Finalizer\CopyExportFiles:
 *       dest: ./some/dir
 *       purge: true
 * ```
 *
 * If the **dest** path starts with ".", it will be relative to the build directory.
 *
 * If you set **purge** to true, it will remove all files in the destination directory on
 * the first run.
 *
 * @ListItem(list="finalizers", link="/usage/finalizers/CopyExportFiles")
 */
class CopyExportFiles extends Finalizer
{

    /**
     * @param Config $config
     */
    public function run(Config $config)
    {
        $dest = $this->getMandatoryParam('dest');

        $dest = new Directory($dest, $config->getBuildDir());

        $filesystem = new Filesystem();

        if ($this->getParam('purge')) {
            $filesystem->purge($dest);
        }

        $filesystem->copy($config->getExportDir(), $dest);
    }
}
