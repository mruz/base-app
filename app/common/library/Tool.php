<?php

/**
 * Tool Library
 * 
 * @package     base-app
 * @category    Library
 * @version     1.1
 */

namespace Baseapp\Library;

class Tool
{

    /**
     * Minify css and js collection
     * 
     * @return  void
     */
    public function assetsMinification()
    {
        foreach ($this->assets->getCss() as $resource) {

            $min = new \Phalcon\Assets\Filters\Cssmin();
            $resource->setTargetUri('min/' . $resource->getPath());

            if (!is_dir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath())))
                mkdir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath()), 0777, TRUE);

            if (md5($min->filter($resource->getContent())) != md5_file(ROOT_PATH . '/public/min/' . $resource->getPath()))
                file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
        }
        
        foreach ($this->assets->getJs() as $resource) {

            $min = new \Phalcon\Assets\Filters\Jsmin();
            $resource->setTargetUri('min/' . $resource->getPath());

            if (!is_dir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath())))
                mkdir(dirname(ROOT_PATH . '/public/min/' . $resource->getPath()), 0777, TRUE);

            if (md5($min->filter($resource->getContent())) != md5_file(ROOT_PATH . '/public/min/' . $resource->getPath()))
                file_put_contents(ROOT_PATH . '/public/min/' . $resource->getPath(), $min->filter($resource->getContent()));
        }
    }
    
    /**
     * Replace CamelCase and Underscores to spaces
     * 
     * @param   string  $str
     * @param   char    $space
     * @return  string
     */
    public static function label($str, $space = ' ')
    {
        $str = preg_replace('/(?<=\\w)(?=[A-Z])/', $space . "$1", $str);
        return $space === ' ' ? ucfirst(trim(str_replace('_', ' ', strtolower($str)))) : $str;
    }

    /**
     * Prepare HTML pagination.
     *
     * First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
     * 
     * @param   string   $url       URL with pagination
     * @param   object   $page      Phalcon Paginator object
     * @param   string   $hook      Hook in URL to adding, eg #pages
     * @param   string   $class     CSS class to adding to div
     * @param   int      $count_out Number of page links in the begin and end of whole range
     * @param   int      $count_in  Number of page links on each side of current page
     * @return  string
     */
    public static function pagination($url, $page, $hook = '', $class = 'pagination', $count_out = 0, $count_in = 2)
    {
        if ($page->total_pages < 2)
            return;
        // Beginning group of pages: $n1...$n2
        $n1 = 1;
        $n2 = min($count_out, $page->total_pages);

        // Ending group of pages: $n7...$n8
        $n7 = max(1, $page->total_pages - $count_out + 1);
        $n8 = $page->total_pages;

        // Middle group of pages: $n4...$n5
        $n4 = max($n2 + 1, $page->current - $count_in);
        $n5 = min($n7 - 1, $page->current + $count_in);
        $use_middle = ($n5 >= $n4);

        // Point $n3 between $n2 and $n4
        $n3 = (int) (($n2 + $n4) / 2);
        $use_n3 = ($use_middle && (($n4 - $n2) > 1));

        // Point $n6 between $n5 and $n7
        $n6 = (int) (($n5 + $n7) / 2);
        $use_n6 = ($use_middle && (($n7 - $n5) > 1));

        // Links to display as array(page => content)
        $links = array();

        // Generate links data in accordance with calculated numbers
        for ($i = $n1; $i <= $n2; $i++)
            $links[$i] = $i;

        if ($use_n3)
            $links[$n3] = '&hellip;';

        for ($i = $n4; $i <= $n5; $i++)
            $links[$i] = $i;

        if ($use_n6)
            $links[$n6] = '&hellip;';

        for ($i = $n7; $i <= $n8; $i++)
            $links[$i] = $i;

        //prepare div and ul
        $html = '<div class="' . $class . '"><ul>';

        //prepare First button
        if ($page->current != $page->first)
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $hook, 'rel' => 'first', __('First'))) . '</li>';
        else
            $html .= '<li class="disabled"><span>' . __('First') . '</span></li>';

        $char = strpos($url, '?') !== FALSE ? '&amp;' : '?';

        //prepare Previous button
        if ($page->current > $page->before)
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->before . $hook, 'rel' => 'prev', 'title' => __('Previous'), '«')) . '</li>';
        else
            $html .= '<li class="disabled"><span>«</span></li>';

        //prepare Pages
        $pages = array();
        foreach ($links as $number => $content) {
            if ($number === $page->current)
                $pages[] = '<li class="active"><span>' . $content . '</span></li>';
            else
                $pages[] = '<li' . ($content == '&hellip;' ? ' class="disabled"' : '') . '>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $number . $hook, $content)) . '</li>';
        }

        $html .= implode('', $pages);

        //prepare Next button
        if ($page->current < $page->next)
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->next . $hook, 'rel' => 'next', 'title' => __('Next'), '»')) . '</li>';
        else
            $html .= '<li class="disabled"><span>»</span></li>';

        //prepare Last button
        if ($page->current != $page->last)
            $html .= '<li>' . \Phalcon\Tag::linkTo(array($url . $char . 'page=' . $page->last . $hook, 'rel' => 'last', __('Last'))) . '</li>';
        else
            $html .= '<li class="disabled"><span>' . __('Last') . '</span></li>';

        //close ul and div
        $html .= '</ul></div>';

        return $html;
    }

    
    public static function registerVolt($volt)
    {
        $volt->setOptions(array(
            'compiledPath' => function($templatePath) {
                $templatePath = strstr($templatePath, '/app');
                $dirName = dirname($templatePath);

                if (!is_dir(ROOT_PATH . '/app/common/cache/volt' . $dirName)) {
                    mkdir(ROOT_PATH . '/app/common/cache/volt' . $dirName, 0777, TRUE);
                }
                return ROOT_PATH . '/app/common/cache/volt' . $dirName . '/' . basename($templatePath, '.volt') . '.php';
            },
            'compileAlways' => TRUE
        ));

        $compiler = $volt->getCompiler();

        $compiler->addExtension(new \Baseapp\Extension\VoltPHPFunctions());

        $compiler->addFunction('debug', function($resolvedArgs) {
                    return '\Baseapp\Library\Debug::vars(' . $resolvedArgs . ')';
                });

        $compiler->addFilter('isset', function($resolvedArgs) {
                    return '(isset(' . $resolvedArgs . ') ? ' . $resolvedArgs . ' : NULL)';
                });

        $compiler->addFilter('label', function($resolvedArgs) {
                    return '\Baseapp\Library\Tool::label(' . $resolvedArgs . ')';
                });

        return $volt;
    }
}
