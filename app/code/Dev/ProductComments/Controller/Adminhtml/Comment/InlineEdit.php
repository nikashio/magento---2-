<?php

namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Exception;
use Magento\Cms\Model\Block;
use Magento\Backend\App\Action;
use Dev\ProductComments\Model\Comment;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var Comment
     */
    private $commentModel;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Comment $commentModel
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->commentModel = $commentModel;
    }

    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    /** @var Block $block */
                    $model = $this->commentModel->load($modelid);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                    } catch (Exception $e) {
                        $messages[] = "[Mymodel ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
