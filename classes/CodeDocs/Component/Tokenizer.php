<?php
namespace CodeDocs\Component;

class Tokenizer
{

    /**
     * @param string $file
     *
     * @return string[]
     */
    public function getClassesOfFile($file)
    {
        $content = file_get_contents($file);
        $tokens = token_get_all($content);

        $current = null;
        $namespace = '';
        $className = '';
        $classWhitespacePassed = false;
        $classes = [];

        foreach ($tokens as $token) {
            $tokenType = is_array($token) ? $token[0] : null;

            switch ($tokenType) {
                case T_NAMESPACE:
                case T_CLASS:
                case T_INTERFACE:
                    $current = $tokenType;
                    $className = '';
                    break;

                case T_STRING:
                case T_NS_SEPARATOR:
                    if ($current === T_NAMESPACE) {
                        $namespace .= $token[1];
                    } elseif ($current) {
                        $className .= $token[1];
                    }
                    break;

                case T_WHITESPACE:
                    if (($current === T_CLASS || $current === T_INTERFACE)
                        && $classWhitespacePassed === false
                    ) {
                        $className .= '\\';
                        $classWhitespacePassed = true;
                    }
                    break;

                default:
                    $current = null;
                    break;
            }

            if ($current === null && $classWhitespacePassed === true && trim($className, '\\')) {
                $classes[] = $namespace . $className;
                $classWhitespacePassed = false;
            }
        }

        return $classes;
    }
}
