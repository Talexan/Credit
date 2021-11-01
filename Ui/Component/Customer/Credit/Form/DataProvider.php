<?php
namespace Talexan\credit\Ui\Component\Customer\Credit\Form;

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
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Request $request
     * @param CustomerRepositoryInterface $customerRepositoryInterface
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
        CustomerRepositoryInterface $customerRepositoryInterface,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->request = $request;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->collectionFactory = $collectionFactory;
        $this->collection = $this->collectionFactory->create();
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

        $customerId = $this->request->getParam('id');

        if($customerId){
            try {
                    $model = $this->customerRepositoryInterface->getById($customerId);
                    $amountCoins = $model->getCustomAttribute('customer_coins');//->getValue();        
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                return $data;
            }
            $data[$customerId] = ['customer_id' => $customerId, 
                                  'customer_coins' => $amountCoins,
                                  'coins_received' => '0'];
        }

        return $data;
    }
}