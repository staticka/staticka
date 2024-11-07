<?php

namespace Staticka\Depots;

use Staticka\Page;
use Staticka\System;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageDepot
{
    const TEMPLATE = '---
name: [TITLE]
link: /[LINK]
title: [TITLE]
description: [DESCRIPTION]
tags:
category:
---

# [TITLE]';

    const SORT_ASC = 0;

    const SORT_DESC = 1;

    /**
     * @var \Staticka\System
     */
    protected $app;

    /**
     * @var string[]
     */
    protected $fields = array();

    /**
     * @param \Staticka\System $app
     * @param string[]         $fields
     */
    public function __construct(System $app, $fields = array())
    {
        $this->app = $app;

        $this->fields = $fields;
    }

    /**
     * @param array<string, string> $data
     *
     * @return \Staticka\Page
     */
    public function create($data)
    {
        $path = $this->app->getPagesPath();

        // @codeCoverageIgnoreStart
        if (! is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        // @codeCoverageIgnoreEnd

        if ($timezone = $this->app->getTimezone())
        {
            date_default_timezone_set($timezone);
        }

        $file = $this->setFilename($data);

        $data = $this->setTemplate($data);

        file_put_contents($file, $data);

        return new Page($file);
    }

    /**
     * @param integer $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $page = $this->find($id);

        $deleted = false;

        if ($page && $file = $page->getFile())
        {
            $deleted = unlink($file);
        }

        return $deleted;
    }

    /**
     * @param integer $id
     *
     * @return \Staticka\Page|null
     */
    public function find($id)
    {
        $result = null;

        foreach ($this->get() as $page)
        {
            if ($page->getId() === ((int) $id))
            {
                $result = $this->parsePage($page);

                break;
            }
        }

        return $result;
    }

    /**
     * @param string $link
     *
     * @return \Staticka\Page|null
     */
    public function findByLink($link)
    {
        $result = null;

        // Link should always start with "/" ---
        if ($link[0] !== '/')
        {
            $link = '/' . $link;
        }
        // -------------------------------------

        foreach ($this->get() as $page)
        {
            if ($page->getLink() === $link)
            {
                $result = $this->parsePage($page);

                break;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     *
     * @return \Staticka\Page|null
     */
    public function findByName($name)
    {
        $result = null;

        foreach ($this->get() as $page)
        {
            $pageName = strtolower((string) $page->getName());

            if ($pageName === strtolower($name))
            {
                $result = $this->parsePage($page);

                break;
            }
        }

        return $result;
    }

    /**
     * @return \Staticka\Page[]
     */
    public function get()
    {
        return $this->app->getPages();
    }

    /**
     * @param integer $sort
     *
     * @return array<string, mixed>[]
     */
    public function getAsData($sort = self::SORT_ASC)
    {
        $pages = $this->get();

        if ($sort === self::SORT_DESC)
        {
            $pages = array_reverse($pages);
        }

        $items = array();

        foreach ($pages as $page)
        {
            $items[] = $page->getData();
        }

        return $items;
    }

    /**
     * @return string[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @link https://stackoverflow.com/a/2103815
     *
     * @param string $text
     *
     * @return string
     */
    public function getSlug($text)
    {
        // Convert to entities --------------------------
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------

        // Regex to convert accented chars into their closest a-z ASCII equivelent --------------
        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';

        /** @var string */
        $text = preg_replace($pattern, '$1', $text);
        // --------------------------------------------------------------------------------------

        // Convert back from entities -------------------------
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------------

        // Any straggling caracters that are not strict alphanumeric are replaced with a dash ---
        /** @var string */
        $text = preg_replace('~[^0-9a-z]+~i', '-', $text);
        // --------------------------------------------------------------------------------------

        // Trim / cleanup / all lowercase ---
        return strtolower(trim($text, '-'));
        // ----------------------------------
    }

    /**
     * @param integer              $id
     * @param array<string, mixed> $data
     *
     * @return \Staticka\Page|null
     */
    public function update($id, $data)
    {
        $page = $this->find($id);

        // @codeCoverageIgnoreStart
        if (! $page)
        {
            return null;
        }
        // @codeCoverageIgnoreEnd

        $file = $page->getFile();

        // @codeCoverageIgnoreStart
        if (! $file)
        {
            return null;
        }
        // @codeCoverageIgnoreEnd

        $body = $page->getBody();

        if (array_key_exists('body', $data))
        {
            /** @var string */
            $body = $data['body'];
        }

        $data = array_merge($page->getData(), $data);

        // The following excluded should not be saved to file ---
        $excluded = array('body', 'created_at', 'html', 'id');

        foreach ($excluded as $item)
        {
            if (array_key_exists($item, $data))
            {
                unset($data[$item]);
            }
        }
        // ------------------------------------------------------

        $dump = Yaml::dump($data);

        $md = '---' . PHP_EOL;
        $md .= str_replace('\'null\'', '', $dump);
        $md .= '---' . PHP_EOL . PHP_EOL;

        $md .= $body;

        file_put_contents($file, $md);

        return $this->find($id);
    }

    /**
     * @param \Staticka\Page $page
     *
     * @return \Staticka\Page
     */
    protected function parsePage(Page $page)
    {
        $parser = $this->app->getParser();

        return $parser->parsePage($page);
    }

    /**
     * @param array<string, string> $data
     *
     * @return string
     */
    protected function setFilename($data)
    {
        $slug = $this->getSlug($data['name']);

        if (array_key_exists('link', $data))
        {
            $slug = str_replace('/', '', $data['link']);
        }

        $prefix = date('YmdHis');

        $file = $prefix . '_' . $slug . '.md';

        $path = $this->app->getPagesPath();

        return (string) $path . '/' . $file;
    }

    /**
     * @param array<string, string> $data
     *
     * @return string
     */
    protected function setTemplate($data)
    {
        $md = self::TEMPLATE;

        $exists = array_key_exists('description', $data);

        $text = $exists ? $data['description'] : '';

        $md = str_replace('[TITLE]', $data['name'], $md);

        $md = str_replace('[DESCRIPTION]', $text, $md);

        $slug = $this->getSlug($data['name']);

        $exists = array_key_exists('link', $data);

        $slug = $exists ? $data['link'] : $slug;

        $md = str_replace('[LINK]', $slug, $md);

        return str_replace('link: //', 'link: /', $md);
    }
}
