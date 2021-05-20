<?php


namespace ExampleComApiClient;


class CommentCollection
{
    /**
     * @var array
     */
    private array $storage = [];

    /**
     * @return array
     */
    public function getCommentsArray(): array
    {
        return $this->storage;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        $this->storage[] = $comment;
        return $this;
    }
}