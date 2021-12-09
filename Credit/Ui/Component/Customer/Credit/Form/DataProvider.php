<?php
namespace Talexan\credit\Ui\Component\Customer\Credit\Form;

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
        return [];
        $customerId = 0;

        if (!empty($this->request)) {
            $customerId = $this->request->getParam('id');
        }

        if ($customerId) {
            $data[$customerId] = [
                'customer_id' => $customerId,
                'input_coins' => '',
                'occasion' => ''];
        } else {
            // display error message
            $this->messageManager->addErrorMessage('Unknown customer');
            return parent::getData();
        }
        return $data;
    }
}
