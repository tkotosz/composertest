<?php

namespace Tkotosz\CliAppWrapper\Config;

class WrappedAppConfig
{
    /** @var array */
    private $config;

    public static function fromArray(array $config): self
    {
        if (!isset($config['extensions'])) {
            $config['extensions'] = [];
        }

        return new self($config);
    }

    public function extensions(): array
    {
        return $this->config['extensions'];
    }

    public function addExtension(string $requestedExtensions): void
    {
        $found = false;
        foreach ($this->config['extensions'] as $extension) {
            if ($extension['name'] === $requestedExtensions) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->config['extensions'][] = [
                'name' => $requestedExtensions,
                'version' => '*'
            ];
        }
    }

    public function removeExtension(string $requestedExtensions)
    {
        foreach ($this->config['extensions'] as $key => $extension) {
            if ($extension['name'] === $requestedExtensions) {
                unset($this->config['extensions'][$key]);
                break;
            }
        }
    }

    public function toArray(): array
    {
        return $this->config;
    }

    private function __construct(array $config)
    {
        $this->config = $config;
    }
}