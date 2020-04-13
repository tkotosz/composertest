<?php

namespace Tkotosz\CliAppWrapperApi;

interface ApplicationManager
{
    public function installExtension(string $extension): int;

    public function listExtensions(): array;

    public function removeExtension(string $extension): int;
}