<?php
namespace Msp\Api\Api;

/**
 * Custom Api Interface
 */
interface CustomApiInterface
{
    /**
     * Get All Posts.
     *
     * @return array
     */
    public function getAllPosts(): array;

    /**
     * Get a post by id
     *
     * @param int $id
     * @return array
     */
    public function getPost(int $id): array;


    /**
    * POST for test api
    * @param string[] $data
    * @return string
    */
    public function setData($data);

     /**
     * GET for Post api
     * @param string $title
     * @return string
     */
    public function getEdit($title);
}
