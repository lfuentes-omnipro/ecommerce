<?php
namespace OmniPro\ProductUpdate\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{

    protected $loggerType = Logger:: DEBUG;
    protected $fileName = '/var/log/omnipro.log';
}
