<?php

namespace Tkotosz\FooApp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tkotosz\CliAppWrapperApi\ApplicationManager;

class ExtensionListCommand extends Command
{
    /** @var ApplicationManager */
    private $applicationManager;

    public function __construct(ApplicationManager $applicationManager)
    {
        $this->applicationManager = $applicationManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('extension:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $availableExtensions = $this->applicationManager->findAvailableExtensions();
        $installedExtensions = $this->applicationManager->findInstalledExtensions();

        $list = [];
        foreach ($availableExtensions as $extension => $latestVersion) {
            $list[] = [
                'Name' => $extension,
                'Installed' => isset($installedExtensions[$extension]) ? 'Yes' : 'No',
                'Latest Version' => $latestVersion,
                'Installed Version' => isset($installedExtensions[$extension]) ? $installedExtensions[$extension] : 'None'
            ];
        }

        $table = new Table($output);
        if (count($list) > 0) {
            $table->addRows($list);
            $table->setHeaders(array_keys(array_shift($list)));
            $table->render();
        }

        return 0;
    }
}