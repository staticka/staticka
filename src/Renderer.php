<?php

namespace Staticka;

use Staticka\Contracts\RendererContract;
// TODO: To be removed in v1.0.0.
// Implement own renderer instead.
use Zapheus\Renderer\Renderer as ZapheusRenderer;

/**
 * Renderer
 *
 * @package Staticka
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Renderer extends ZapheusRenderer implements RendererContract
{
}
