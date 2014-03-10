<?php

namespace Baseapp\Library;

/**
 * Tool Library
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */
class Tool
{

    /**
     * Minify css and js collection
     *
     * @package     base-app
     * @version     2.0
     *
     * @return void
     */
    public static function assetsMinification()
    {
        $config = \Phalcon\DI::getDefault()->getShared('config');

        foreach (array('Css', 'Js') as $asset) {
            $get = 'get' . $asset;
            $filter = '\Phalcon\Assets\Filters\\' . $asset . 'min';

            foreach (\Phalcon\DI::getDefault()->getShared('assets')->$get() as $resource) {
                $min = new $filter();
                $resource->setSourcePath(ROOT_PATH . '/public/' . $resource->getPath());
                $resource->setTargetUri('min/' . $resource->getPath());

                if ($config->app->env != 'production') {
                    if (!is_dir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath()))) {
                        $old = umask(0);
                        mkdir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath()), 0777, true);
                        umask($old);
                    }

                    if ($config->app->env == 'development' || !file_exists(ROOT_PATH . '/public/min/' . $resource->getPath())) {
                        file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
                    } elseif (md5($min->filter($resource->getContent())) != md5_file(ROOT_PATH . '/public/min/' . $resource->getPath())) {
                        file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
                    }
                }
            }
        }
    }

    /**
     * Replace CamelCase and under_scores to spaces
     *
     * @package     base-app
     * @version     2.0
     *
     * @param string $str string to replace to human readable
     * @param char $space default spacer
     *
     * @return string
     */
    public static function label($str, $space = ' ')
    {
        $str = preg_replace('/(?<=\\w)(?=[A-Z])/', $space . "$1", $str);
        return $space === ' ' ? ucfirst(trim(str_replace('_', ' ', strtolower($str)))) : $str;
    }

    /**
     * Prepare HTML pagination.
     * First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
     *
     * @package     base-app
     * @version     2.0
     *
     * @param   string   $url       URL with pagination
     * @param   object   $page      Phalcon Paginator object
     * @param   string   $hook      Hook in URL to adding, eg #pages
     * @param   string   $class     CSS class to adding to div
     * @param   int      $countOut Number of page links in the begin and end of whole range
     * @param   int      $countIn  Number of page links on each side of current page
     *
     * @return  string
     */
    public static function pagination($url, $page, $hook = '', $class = 'pagination', $countOut = 0, $countIn = 2)
    {
        if ($page->total_pages < 2) {
            return;
        }
        // Beginning group of pages: $n1...$n2
        $n1 = 1;
        $n2 = min($countOut, $page->total_pages);

        // Ending group of pages: $n7...$n8
        $n7 = max(1, $page->total_pages - $countOut + 1);
        $n8 = $page->total_pages;

        // Middle group of pages: $n4...$n5
        $n4 = max($n2 + 1, $page->current - $countIn);
        $n5 = min($n7 - 1, $page->current + $countIn);
        $useMiddle = ($n5 >= $n4);

        // Point $n3 between $n2 and $n4
        $n3 = (int) (($n2 + $n4) / 2);
        $useN3 = ($useMiddle && (($n4 - $n2) > 1));

        // Point $n6 between $n5 and $n7
        $n6 = (int) (($n5 + $n7) / 2);
        $useN6 = ($useMiddle && (($n7 - $n5) > 1));

        // Links to display as array(page => content)
        $links = array();

        // Generate links data in accordance with calculated numbers
        for ($i = $n1; $i <= $n2; $i++) {
            $links[$i] = $i;
        }

        if ($useN3) {
            $links[$n3] = '&hellip;';
        }

        for ($i = $n4; $i <= $n5; $i++) {
            $links[$i] = $i;
        }

        if ($useN6) {
            $links[$n6] = '&hellip;';
        }

        for ($i = $n7; $i <= $n8; $i++) {
            $links[$i] = $i;
        }

        //prepare div and ul
        $html = '<div class="' . $class . '"><ul>';

        //prepare First button
        if ($page->current != $page->first) {
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $hook, 'rel' => 'first', __('First'))) . '</li>';
        } else {
            $html .= '<li class="disabled"><span>' . __('First') . '</span></li>';
        }

        $char = strpos($url, '?') !== false ? '&amp;' : '?';

        //prepare Previous button
        if ($page->current > $page->before) {
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->before . $hook, 'rel' => 'prev', 'title' => __('Previous'), '«')) . '</li>';
        } else {
            $html .= '<li class="disabled"><span>«</span></li>';
        }

        //prepare Pages
        $pages = array();
        foreach ($links as $number => $content) {
            if ($number === $page->current) {
                $pages[] = '<li class="active"><span>' . $content . '</span></li>';
            } else {
                $pages[] = '<li' . ($content == '&hellip;' ? ' class="disabled"' : '') . '>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $number . $hook, $content)) . '</li>';
            }
        }

        $html .= implode('', $pages);

        //prepare Next button
        if ($page->current < $page->next) {
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->next . $hook, 'rel' => 'next', 'title' => __('Next'), '»')) . '</li>';
        } else {
            $html .= '<li class="disabled"><span>»</span></li>';
        }

        //prepare Last button
        if ($page->current != $page->last) {
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->last . $hook, 'rel' => 'last', __('Last'))) . '</li>';
        } else {
            $html .= '<li class="disabled"><span>' . __('Last') . '</span></li>';
        }

        //close ul and div
        $html .= '</ul></div>';

        return $html;
    }

    /**
     * Register the Volt engines
     *
     * @package     base-app
     * @version     2.0
     *
     * @param object $view Phalcon\Mvc\View
     * @param object $di dependency Injection
     *
     * @return array array of template engines
     */
    public static function registerEngines($view, $di)
    {
        $config = \Phalcon\DI::getDefault()->getShared('config');

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(array(
            // Don't check on 'production' for differences between the template file and its compiled path
            // Compile always on 'development', on 'testing'/'staging' only checks for changes in the children templates
            'stat' => $config->app->env == 'production' ? false : true,
            'compileAlways' => $config->app->env == 'development' ? true : false,
            'compiledPath' => function($templatePath) {
        list($junk, $path) = explode(ROOT_PATH, $templatePath);
        $dir = dirname($path);
        $file = basename($path, '.volt');

        if (!is_dir(ROOT_PATH . '/app/common/cache/volt' . $dir)) {
            $old = umask(0);
            mkdir(ROOT_PATH . '/app/common/cache/volt' . $dir, 0777, true);
            umask($old);
        }
        return ROOT_PATH . '/app/common/cache/volt' . $dir . '/' . $file . '.phtml';
    }
        ));

        $compiler = $volt->getCompiler();
        $compiler->addExtension(new \Baseapp\Extension\VoltStaticFunctions());
        $compiler->addExtension(new \Baseapp\Extension\VoltPHPFunctions());

        return array(
            // Try to load .phtml file from ViewsDir first,
            ".phtml" => "Phalcon\Mvc\View\Engine\Php",
            ".volt" => $volt,
        );
    }

}
