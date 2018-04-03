<?php

namespace Staticka;

use Zapheus\Provider\ConfigurationInterface;
use Zapheus\Renderer\RendererInterface;

/**
 * Layout
 *
 * @package Staticka
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Layout
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \Staticka\Matter
     */
    protected $matter;

    /**
     * @var \Staticka\Staticka
     */
    protected $staticka;

    /**
     * @var \Zapheus\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * Initializes the layout instance.
     *
     * @param \Zapheus\Renderer\RendererInterface $renderer
     * @param \Staticka\Staticka                  $staticka
     * @param array                               $data
     */
    public function __construct(RendererInterface $renderer, Staticka $staticka, array $data)
    {
        $data['config'] = $staticka;

        $this->staticka = $staticka;

        $this->renderer = $renderer;

        $this->data = $data;

        $this->matter = new Matter;
    }

    /**
     * Renders the specified HTML content with a template.
     *
     * @param  string $name
     * @param  string $html
     * @return string
     */
    public function render($name, $content)
    {
        list($data, $content) = $this->matter->parse($content);

        $data = array_merge($data, (array) $this->data);

        $data['title'] = isset($data['title']) ? $data['title'] : '';

        $output = $this->staticka->content()->make($content);

        if ($data['title'] === '') {
            preg_match('/<h1>(.*?)<\/h1>/', $output, $matches);

            isset($matches[1]) && $data['title'] = $matches[1];
        }

        $data['content'] = (string) $output;

        return $this->renderer->render($name, $data);
    }
}
