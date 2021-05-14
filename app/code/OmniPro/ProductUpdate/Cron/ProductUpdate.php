<?php
namespace OmniPro\ProductUpdate\Cron;

class ProductUpdate
{
    /**
     * @param \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \OmniPro\ProductUpdate\Model\ProductUpdate
     */
    private $productUpdate;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \OmniPro\ProductUpdate\Model\ProductUpdate $productUpdate
    )
    {
        $this->logger = $logger;
        $this->productUpdate = $productUpdate;
        
    }

    public function execute () {
        $this->productUpdate->process();
    }
}