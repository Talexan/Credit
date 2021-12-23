<?php
namespace Talexan\credit\Ui\Component\Customer\Credit\Grid;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\RequestInterface as Request;
use Magento\Ui\DataProvider\Modifier\PoolInterface as PoolInterface;
use Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory as CollectionFactory;

/**
 * DataProvider for user edit form ui.
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var CustomerRepositoryInterface $customerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Request $request
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Request $request,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->request = $request;
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $customerId = (int)$this->getRequest()->getParam('id');

        if ($customerId) {
            try {
                $this->getCollection()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', $customerId)
                    ->setOrder('created_at', 'DESC')
                    ->load();
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                return parent::getData();
            }

            return $this->getCollection()->getData();
        }

        return parent::getData();
    }
}
