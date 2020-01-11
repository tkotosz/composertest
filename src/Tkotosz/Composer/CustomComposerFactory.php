<?php

namespace Tkotosz\Composer;

use Composer\Factory;
use Composer\IO\IOInterface;

class CustomComposerFactory extends Factory
{
    /** @var string|null */
    private static $vendorDir = null;

    public static function createCustomComposer(IOInterface $io, string $composerFile, string $vendorDir)
    {
        self::$vendorDir = $vendorDir;

        return self::create($io, $composerFile);
    }

    public static function createConfig(IOInterface $io = null, $cwd = null)
    {
        $config = parent::createConfig($io, $cwd);

        if (self::$vendorDir !== null) {
            $config->merge(['config' => ['vendor-dir' => self::$vendorDir]]);
        }

        return $config;
    }
}