<?php

namespace TDN\Bundle\DocumentBundle\Twig;


class MarkdownExtension extends \Twig_Extension
{
    private $markdown = array("#\[image\]([^\[]+)\[/image\]#", "#\[url=([^\]]+)\]([^\[]+)\[/url\]#");
    private $html = array('<image src="$1" alt="" />', '<a href="$1">$2</a>');

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdownFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
        );
    }

    public function markdownFilter($body)
    {
        return preg_replace($markdown, $html, $body);
    }

    public function getName()
    {
        return 'markdown_extension';
    }
}