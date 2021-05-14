<?php

namespace OmniPro\ProductUpdate\Helper;

use Algolia\AlgoliaSearch\Helper\Data;
use Directory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Reader
{
    /**
     * @param \Magento\Framework\Filesystem
     */
    private $filesystem;
    /**
     * @param \Magento\Framework\File\Csv
     */
    private $csv;
    /**
     * @param \Magento\Framework\Filesystem\Driver\File
     */
    private $file;
    /**
     * @param \OmniPro\ProductUpdate\Logger\Logger
     */
    private $logger;
    /**
     * @param \OmniPro\ProductUpdate\Helper\Email
     */
    private $email;
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem\Driver\File $file,
        \OmniPro\ProductUpdate\Logger\Logger $logger,
        \OmniPro\ProductUpdate\Helper\Email $email
    ) {
        $this->filesystem = $filesystem;
        $this->csv = $csv;
        $this->file = $file;
        $this->logger = $logger;
        $this->email = $email;
    }
    public function validateSku($sku)
    {
        if (!empty($sku)) return true;
        else return false;
    }
    public function validateName($name)
    {
        if (!empty($name)) return true;
        else return false;
    }
    public function validatePrice($price)
    {
        if (is_numeric($price)) {
            if ($price >= 0) return true;
            else return false;
        } else return false;
    }
    public function validateStock($Stock)
    {
        if (is_numeric($Stock)) {
            if ($Stock >= 0) return true;
            else return false;
        } else return false;
    }

    public function readCsv()
    {
        $datos = array();
        $datos['products'] = array();
        $datos['value'] = array();

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $pathFile = $mediaDirectory->getAbsolutePath('update/data.csv');
        if ($this->file->isExists($pathFile)) {
            $this->csv->setDelimiter('|');
            $data = $this->csv->getData($pathFile);
            $dataNew = [];
            if (!empty($data)) {
                $subdata = array_slice($data, 1);
                $datos['value'][] = count($subdata);
                foreach ($subdata  as $key => $value) {
                    $sku = $value[0];
                    $name = $value[1];
                    $price = $value[2];
                    $stock = $value[6];

                    if (!$this->validatePrice($price)) {
                        unset($subdata[$key + 1]);
                        $this->logger->debug("Campo vacio, el precio es requerido");
                    } elseif (!$this->validateSku($sku)) {
                        unset($subdata[$key + 1]);
                        $this->logger->debug('Sku vacio, campo requerido');
                    } elseif (!$this->validateStock($stock)) {
                        unset($subdata[$key + 1]);
                        $this->logger->debug('Campo Stock es requerido');
                    } elseif (!$this->validateName($name)) {
                        unset($subdata[$key + 1]);
                        $this->logger->debug('Nombre vacio, el nombre es requerido');
                    }
                }
                $datos['products'] = $subdata;
            }
        }
        $datos['value'][] = count($datos['products']);
        return $datos;
    }
}
