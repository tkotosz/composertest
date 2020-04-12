<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface Extension
{
    public function initialize(): void;
    public function configure(): void;
    public function load(CommandRegistry $commandRegistry): void;
}