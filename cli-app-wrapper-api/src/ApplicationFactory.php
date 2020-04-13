<?php

namespace Tkotosz\CliAppWrapperApi;

interface ApplicationFactory
{
    public static function create(array $extensions, ApplicationManager $applicationManager): Application;
}