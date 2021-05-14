<?php

namespace OmniPro\ProductUpdate\Helper;

use \Magento\Framework\App\Helper\Context;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @param \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @param \Magento\Framework\Escaper
     */
    private $escaper;

    /**
     * @param \Magento\Framework\Logger
     */
    private $logger;

    public function __construct(
        Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Escaper $escaper
    ) {

        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->escaper = $escaper;
        $this->logger = $context->getLogger();
        parent::__construct($context);
    }
    public function sendEmail($count, $succes, $failed)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $destEmail = $this->scopeConfig->getValue('omnipro_product_update_general/dest_email', $storeScope);
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml('Test'),
                'email' => $this->escaper->escapeHtml('luz.fuentes@omni.pro')
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('omnipro_product_update_general_email_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'templateVarCount'  => $count,
                    'templateVarSucces'  => $succes,
                    'templateVarFailed'  => $failed,
                ])
                ->setFromByScope($sender)
                ->addTo('admin@omni.pro')
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
