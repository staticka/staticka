<?php

namespace Staticka;

use Zapheus\Provider\ConfigurationInterface;
use Zapheus\Renderer\RendererInterface;

/**
 * Layout
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Layout
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \Staticka\Website
     */
    protected $website;

    /**
     * @var \Zapheus\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * Initializes the layout instance.
     *
     * @param \Zapheus\Renderer\RendererInterface $renderer
     * @param \Staticka\Website                   $website
     * @param array                               $data
     */
    public function __construct(RendererInterface $renderer, Website $website, array $data)
    {
        $data['config'] = $website;

        $this->website = $website;

        $this->renderer = $renderer;

        $this->data = (array) $data;
    }

    /**
     * Renders the specified HTML content with a template.
     *
     * @param  string $name
     * @param  string $content
     * @return string
     */
    public function render($name, $content)
    {
        list($data, $content) = (array) Matter::parse($content);

        $data = array_merge($data, (array) $this->data);

        $data['title'] = isset($data['title']) ? $data['title'] : '';

        $output = $this->website->content()->make($content);

        if ($data['title'] === '') {
            preg_match('/<h1>(.*?)<\/h1>/', $output, $matches);

            isset($matches[1]) && $data['title'] = $matches[1];
        }

        $data['content'] = (string) $output;

        return $this->renderer->render($name, $data);
    }
}
