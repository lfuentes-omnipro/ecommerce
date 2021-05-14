<?php

namespace OmniPro\Update\Model;

class ProductUpdate
{
    /**
     * @param \OmniPro\Update\Helper\Email
     */
    private $email;
    /**
     * @param \OmniPro\Update\Logger\Logger
     */
    private $logger;
    /**
     * @param \OmniPro\Update\Helper\Reader
     */
    private $reader;
    /**
     * @param \Magento\Framework\App\State
     */
    private $state;

    /**
     * @param \OmniPro\Update\Helper\Create
     */
    private $create;

    public function __construct(
        \OmniPro\Update\Helper\Email $email,
        \OmniPro\Update\Logger\Logger $logger,
        \OmniPro\Update\Helper\Reader $reader,
        \Magento\Framework\App\State $state,
        \OmniPro\Update\Helper\Create $create
    ) {
        $this->email = $email;
        $this->logger = $logger;
        $this->reader = $reader;
        $this->state = $state;
        $this->create = $create;
    }

    public function process()
    {
        $this->logger->debug('hola');
         $this->state->setAreaCode('frontend');

        $productos = $this->reader->readCsv();
        $this->logger->debug('numero de registro' . $productos['value'][0]);
        $this->logger->debug('registro correcto' . $productos['value'][1]);
        $this->logger->debug('registro errores' . ($productos['value'][0] - $productos['value'][1]));

        $this->create->crearProductos($productos['products']);
        $this->email->sendEmail($productos['value'][0], $productos['value'][1], ($productos['value'][0] - $productos['value'][1]));
        // $this->email->sendEmail(1,2,3);
    }
}
