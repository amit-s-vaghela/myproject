<?php
namespace Msp\Api\Model;

use Psr\Log\LoggerInterface;

class CustomApi 
{
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getAllPosts(): array
    {
        return [
            [
                "id" => 1,
                "title" => "My Post 1 Msp",
                "categories" => ["my posts", "custom posts"],
                "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
            ],
            [
                "id" => 2,
                "title" => __("My Post 2 Msp"),
                "categories" => ["my posts", "custom post2"],
                "description" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getPost(int $id): array
    {
        $allposts = $this->getAllPosts();
        $post = [];
        foreach ($allposts as $item) {
            if ($id == $item['id']) {
                $post[] = $item;
                break;
            }
        }

        return $post;
    }

    /**
    * {@inheritdoc}
    */
    public function setData($data)
    {
        $response = ['success' => false];
        try {
            echo "<pre>"; print_r($data);
           
            $response = ['success' => true, 'message' => $data];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        $returnArray = json_encode($response);
        return $response; 

        //echo "<pre>"; print_r($data); die;
        //return "test";
    } 

    // http://202.131.107.107:8013/a_magento244/pub/rest/V1/custom-api/test/

    //{"data":{"name":"Amit", "mobile":7777}}

}
