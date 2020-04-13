<?php

namespace Tkotosz\CliAppWrapperApi;

interface ApplicationManager
{
    public function getApplicationConfig(): ApplicationConfig;

    public function getWorkingDirectory(): string;

    public function getUserConfig(): array;

    public function installExtension(string $extension): int;

    public function removeExtension(string $extension): int;

    public function findInstalledExtensionClasses(): array;

    public function findInstalledExtensions(): array;

    public function findAvailableExtensions(): array;
}