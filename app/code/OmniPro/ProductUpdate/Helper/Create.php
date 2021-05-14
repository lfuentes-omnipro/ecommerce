<?php
namespace OmniPro\ProductUpdate\Helper;
class Create
{
    /**
     * @param Magento\Catalog\Api\Data\ProductInterfaceFactory
     */
    private $productFactory;
    /**
     * @param Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @param Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;
    /**
     * @param \Magento\Framework\App\State
     */
    private $state;
    /**
     * @param Magento\Catalog\Api\Data\SpecialPriceInterfaceFactory
     */
    private $specialPriceInterfaceFactory;
    /**
     * @param use Magento\Catalog\Api\SpecialPriceInterface
     */
    private $specialPrice;
    /**
     * @param \Magento\Store\Model\App\Emulation
     */
    private $emulation;
    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;
    /**
     * constructor.
     *
     * @param SpecialPriceInterface $specialPrice
     */
    public function __construct(
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\State $state,
        \Magento\Catalog\Api\Data\SpecialPriceInterfaceFactory $specialPriceInterfaceFactory,
        \Magento\Catalog\Api\SpecialPriceInterface $specialPrice,
        \Magento\Store\Model\App\Emulation $emulation,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
        $this->state = $state;
        $this->specialPriceInterfaceFactory = $specialPriceInterfaceFactory;
        $this->specialPrice = $specialPrice;
        $this->emulation = $emulation;
        $this->dateTime = $dateTime;
    }
    public function crearProductos($dataCsv)
    {
        foreach(array_slice($dataCsv, 1) as $key => $value) {
            /** 
             * @var \Magento\Catalog\Api\Data\ProductInterface $product
             */
            $product = $this->productFactory->create();
            $product->setSku($value[0]);
            $product->setName($value[1]);
            $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
            $product->setVisibility(4);
            $product->setPrice($value[2]);
            $product->setAttributeSetId(4);
            $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $product = $this->productRepository->save($product);
            $stockItem = $this->stockRegistry->getStockItemBySku($product->getSku());
            $stockItem->setIsInStock(1);
            $stockItem->setQty($value[6]);
            $this->stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
            $updateDatetime = new \DateTime();
            $prices[] = $this->specialPriceInterfaceFactory->create()
                ->setSku($product->getSku())
                ->setStoreId(0)
                ->setPrice($value[3])
                ->setPriceFrom($updateDatetime->modify($value[4])->format('Y-m-d H:i:s'))
                ->setPriceTo($updateDatetime->modify($value[5])->format('Y-m-d H:i:s'));
            $this->specialPrice->update($prices);
        }
    }
}