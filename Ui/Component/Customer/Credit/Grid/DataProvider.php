<?php
namespace Talexan\credit\Ui\Component\Customer\Credit\Grid;

use Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory as CollectionFactory;
use Magento\Ui\DataProvider\Modifier\PoolInterface as PoolInterface;
use Magento\Framework\App\RequestInterface as Request;
use \Magento\Customer\Api\CustomerRepositoryInterface;

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
     * @var Talexan\Credit\Model\ResourceModel\Coin\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @param Request $request
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
        Request $request,
        CustomerRepositoryInterface $customerRepositoryInterface,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->request = $request;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {        
        return $this->prepareData(parent::getData());
    }

     /**
     * Prepare form user data
     * @param array $data
     * @return array
     */

    public function prepareData($data){

        $customerId = $this->request->getParam('$customer_id');

        if($customerId){
            try {
                    $collection = $this->collectionFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', $customerId)
                    ->setOrder('created_at','DESC')
                    ->load();        
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                return $data;
            }

            return $collection->getData();
        }

        return $data;
    }
}