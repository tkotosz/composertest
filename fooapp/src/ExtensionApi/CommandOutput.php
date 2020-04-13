<?php

namespace Tkotosz\FooApp\ExtensionApi;

interface CommandOutput
{
    public function writeln(string $text);
}