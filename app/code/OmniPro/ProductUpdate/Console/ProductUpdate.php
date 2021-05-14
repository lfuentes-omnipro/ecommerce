<?php
namespace OmniPro\ProductUpdate\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProductUpdate extends Command
{
    /**
     * @param \OmniPro\ProductUpdate\Model\ProductUpdate
     */
    private $productUpdate;

    public function __construct(
        \OmniPro\ProductUpdate\Model\ProductUpdate $productUpdate
    ) {
        $this->productUpdate = $productUpdate;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('omnipro:product-update');
        $this->setDescription('This is my console command.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->productUpdate->process();
        } catch (\Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
        
        $output->writeln('<info>Update productcs Success</info>'); 
    }
}