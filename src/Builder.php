<?php
namespace CodeDocs;

use ParsedownExtra as Parsedown;

/**
 *
 */
class Builder
{
    /**
     * @var Parsedown
     */
    private $parsedown;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string
     */
    private $configDir;

    /**
     * @var string
     */
    private $extensionsRegex = '(md|markdown)';

    /**
     * @var TemplateData
     */
    private $templateData;

    /**
     * @param Parsedown $parsedown
     * @param string    $configFile
     */
    public function __construct(Parsedown $parsedown, $configFile)
    {
        $this->parsedown = $parsedown;

        $content = file_get_contents($configFile);
        $this->config = json_decode($content, true);
        if (!is_array($this->config)) {
            throw new \Exception('Config file must have a valid JSON content');
        }

        $this->configDir = dirname($configFile);

        $this->templateData = new TemplateData();
    }

    /**
     * Build doc files.
     *
     * @return void
     */
    public function build()
    {
        $srcDir = realpath($this->configDir . '/' . $this->getConfig('src', true));
        if ($srcDir === false || !is_dir($srcDir)) {
            throw new \Exception('"src" is not a valid directory');
        }

        $destDir = $this->configDir . '/' . $this->getConfig('dest', true);
        if (!is_dir($destDir)) {
            $created = mkdir($destDir, 0777, true);
            if (!$created) {
                throw new \Exception('failed to create destination directory');
            }
        }

        $templateDir = $this->getConfig('template') ?: 'default';
        if (!is_dir($templateDir)) {
            $templateDir = __DIR__ . '/../templates/' . $templateDir;
            if (!is_dir($templateDir)) {
                throw new \Exception('invalid template');
            }
        }

        $documentFile = realpath($templateDir . '/document.php');
        if (!$documentFile) {
            throw new \Exception('missing document.php in template');
        }

        $items = $this->parseDocs($srcDir, $destDir);

        $this->templateData->items = $items;
        $this->templateData->name = $this->getConfig('name', true);
        $this->templateData->description = $this->parsedown->text($this->getConfig('description', true));
        $this->templateData->additional = $this->getConfig('additional') ?: [];

        $this->buildHtml($items, $documentFile);

        $indexItem = new TreeItem();
        $indexItem->src = realpath($srcDir . '/index.md');
        $indexItem->dest = $destDir . '/index.html';
        $indexItem->relUrl = 'index.html';
        $this->buildHtmlFile($indexItem, $documentFile);

        $this->copyFolder($templateDir . '/assets', $destDir . '/assets');
    }

    /**
     * Get config value from json file.
     *
     * @param string $name
     * @param bool   $mandatory
     *
     * @return mixed
     */
    private function getConfig($name, $mandatory = false)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } elseif ($mandatory) {
            throw new \Exception('config "' . $name . '" is not set');
        }
        return null;
    }

    /**
     * Parse source documentation folder.
     *
     * @param string        $srcDir
     * @param string        $destDir
     * @param TreeItem|null $parent
     *
     * @return TreeItem[]
     */
    private function parseDocs($srcDir, $destDir, $parent = null)
    {
        $items = [];

        foreach (scandir($srcDir) as $file) {
            if ($file != '.' && $file != '..') {
                if (preg_match('/^[0-9]+-(.+)(|\.' . $this->extensionsRegex . ')$/U', $file, $matches)) {
                    $item = new TreeItem();
                    $name = $matches[1];

                    $item->label = $this->convertNameToLabel($name);
                    $item->relUrl = ($parent ? $parent->relUrl . '/' : '') . $name;
                    $item->src = $srcDir . '/' . $file;
                    $item->dest = $destDir . '/' . $item->relUrl;
                    $item->parent = $parent;

                    if (is_dir($item->src)) {
                        $item->children = $this->parseDocs($item->src, $destDir, $item);
                    } elseif (preg_match('/\.' . $this->extensionsRegex . '$/', $file)) {
                        $item->dest .= '.html';
                        $item->relUrl .= '.html';
                    }

                    $items[] = $item;
                }
            }
        }

        return $items;
    }

    /**
     * Build doc html files for the given items.
     *
     * @param TreeItem[] $items
     * @param string     $templateFile
     *
     * @return void
     */
    private function buildHtml($items, $templateFile)
    {
        foreach ($items as $item) {
            if ($item->children) {
                $this->buildHtml($item->children, $templateFile);
            } else {
                $this->buildHtmlFile($item, $templateFile);
            }
        }
    }

    /**
     * Build a doc html file.
     *
     * @param TreeItem $item
     * @param string   $templateFile
     *
     * @return void
     */
    private function buildHtmlFile(TreeItem $item, $templateFile)
    {
        $depth = $this->getItemDepth($item);

        $data = clone $this->templateData;
        $data->currentItem = $item;
        if ($item->src) {
            $data->content = $this->parsedown->text(file_get_contents($item->src));
        } else {
            $data->content = '';
        }

        $data->baseUrl = str_repeat('../', $depth);

        $content = $this->render($templateFile, $data);

        $dir = dirname($item->dest);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($item->dest, $content);
    }

    /**
     * Render template and return content.
     *
     * @param string       $templateFile
     * @param TemplateData $data
     *
     * @return string
     */
    private function render($templateFile, TemplateData $data)
    {
        ob_start();
        require $templateFile;
        return ob_get_clean();
    }

    /**
     * Copy whole folder.
     *
     * @param string $src
     * @param string $dest
     *
     * @return void
     */
    private function copyFolder($src, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0777, true);
        }
        foreach (scandir($src) as $file) {
            if ($file != '.' && $file != '..') {
                $srcPath = $src . '/' . $file;
                $destPath = $dest . '/' . $file;
                if (is_dir($srcPath)) {
                    $this->copyFolder($srcPath, $destPath);
                } else {
                    copy($srcPath, $destPath);
                }
            }
        }
    }

    /**
     * Convert filename to label.
     *
     * @param string $name
     *
     * @return string
     */
    private function convertNameToLabel($name)
    {
        $name = preg_replace('/(?<!-)-(?!-)/U', ' ', $name);
        $name = preg_replace('/(?<!-)--(?!-)/U', '-', $name);
        $name = preg_replace('/(?<!-)---(?!-)/U', ' - ', $name);
        return $name;
    }

    /**
     * Get depth of item.
     *
     * @param TreeItem $item
     * @param int      $depth
     *
     * @return int
     */
    private function getItemDepth(TreeItem $item, $depth = 0)
    {
        if ($item->parent) {
            return $this->getItemDepth($item->parent, $depth + 1);
        }
        return $depth;
    }
}