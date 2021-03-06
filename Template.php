<?php

namespace Codememory\Components\Big;

use Codememory\Components\Big\Exceptions\TemplateNotExistException;
use Codememory\FileSystem\Interfaces\FileInterface;
use Codememory\Support\Str;

/**
 * Class Template
 * @package Codememory\Components\Big
 *
 * @author  Codememory
 */
class Template
{

    /**
     * @var string
     */
    private string $path;

    /**
     * @var FileInterface
     */
    private FileInterface $filesystem;

    /**
     * @var Utils
     */
    private Utils $utils;

    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * Template constructor.
     *
     * @param string        $path
     * @param FileInterface $filesystem
     * @param Utils         $utils
     */
    public function __construct(string $path, FileInterface $filesystem, Utils $utils)
    {

        $this->path = $path;
        $this->filesystem = $filesystem;
        $this->utils = $utils;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Set parameters(variables) for the open template.
     * These variables can be used in the template itself
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): Template
    {

        $this->parameters = $parameters;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get the entire pattern consisting of an array of strings
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     * @throws TemplateNotExistException
     */
    public function getArrayLineTemplate(): array
    {

        $templateLines = explode(PHP_EOL, $this->getTemplate());
        $lines = [];

        foreach ($templateLines as $index => $value) {
            $lines[++$index] = $value;
        }

        return $lines;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get template if template does not exist an exception will be thrown
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string|null
     * @throws TemplateNotExistException
     */
    public function getTemplate(): ?string
    {

        if (!$this->filesystem->exist($this->getFullPath($this->path))) {
            throw new TemplateNotExistException($this->getFullPath($this->path));
        }

        return file_get_contents($this->filesystem->getRealPath($this->getFullPath($this->path)));

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get an array of added parameters
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getParameters(): array
    {

        return $this->parameters;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get the name of the opened template
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    public function getTemplateName(): string
    {

        return $this->path;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Get full path to open template
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $path
     *
     * @return string
     */
    public function getFullPath(string $path): string
    {

        $pathWithTemplates = trim(Str::asPath($this->utils->getPathWithTemplates()), '/');
        $path = Str::asPath($path);

        return sprintf('%s/%s%s', $pathWithTemplates, $path, Utils::EXTENSION);

    }

}