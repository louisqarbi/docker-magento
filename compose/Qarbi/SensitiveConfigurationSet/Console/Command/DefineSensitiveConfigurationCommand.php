<?php

namespace Qarbi\SensitiveConfigurationSet\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Symfony\Component\Console\Input\ArrayInput;
class DefineSensitiveConfigurationCommand extends Command
{
    const CONFIGURATION = 'configuration';
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('config:define:qarbi_sensitive')
            ->setDescription('Define system configuration with qarbi sensitive')
            ->setDefinition([
                new InputArgument(
                    static::CONFIGURATION,
                    InputArgument::REQUIRED,
                    'Configuration json format'
                )])
        ;
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $configuration = $input->getArgument(static::CONFIGURATION);
            $jsonDecodeConfiguration = json_decode($configuration);
            $output->writeln($configuration);

            foreach ($jsonDecodeConfiguration as $config) {
                $arrayConfig = (array)$config;
                $arguments = new ArrayInput(
                    [
                        'command' => 'config:set:qarbi_sensitive',
                        'path' => $arrayConfig['path'],
                        'value' => $arrayConfig['value'],
                    ]
                );
                try {
                    $returnCode = $this->getApplication()->doRun($arguments, $output);
                    if ($returnCode === Cli::RETURN_SUCCESS) {
                        $output->writeln("<info>Value was saved for path: " . $arrayConfig['path'] . " </info>");
                    } else {
                        $output->writeln("<error>Value was saved failed for path: " . $arrayConfig['path'] . " </error>");
                    }
                } catch (\Exception $e) {
                    $output->writeln('<error>' . $e->getMessage() . '</error>');
                }
            }
            $this->getApplication()->find('config:set:qarbi_sensitive');
            return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}
