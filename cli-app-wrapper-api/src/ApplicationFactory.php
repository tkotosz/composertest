<?php

namespace Tkotosz\CliAppWrapperApi;

interface ApplicationFactory
{
    public static function create(ApplicationManager $applicationManager): Application;
}