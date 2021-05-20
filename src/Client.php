<?php


namespace ExampleComApiClient;


class Client
{
    private string $baseUrl = 'http://example.com';

    public function getComments(): array
    {
        $url = $this->baseUrl . '/comments';

        try {
            $response = $this->sendRequest('GET', $url);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        $data = json_decode($response, 1);
        $commentCollection = new CommentCollection();

        if(isset($data['comments'])) {
            foreach($data as $rawComment) {
                $comment = $this->newComment($rawComment);
                $commentCollection->addComment($comment);
            }
        } else {
            throw new \Exception('Ошибка при получении комментариев');
        }

        return $commentCollection->getCommentsArray();
    }

    public function createComment(string $name, string $text): Comment
    {
        $url = $this->baseUrl . '/comment';

        try {
            $response = $this->sendRequest('POST', $url, compact('name', 'text'));
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        try {
            return $this->uploadComment($response);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function updateComment(int $id, string $name, string $text): Comment
    {
        $url = $this->baseUrl . '/comment/' . $id;

        try {
            $response = $this->sendRequest('PUT', $url, compact('name', 'text'));
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        try {
            return $this->uploadComment($response);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    private function uploadComment(string $response): Comment
    {
        $data = json_decode($response, 1);
        if(isset($data['comment'])) {
            $this->newComment($data['comment']);
        }

        throw new \Exception('Ошибка при загрузке комментария');
    }

    private function newComment(array $commentData): Comment
    {
        $comment = new Comment();
        return $comment
            ->setId((int) $commentData['id'])
            ->setName($commentData['name'])
            ->setText($commentData['text']);
    }

    private function sendRequest(string $method, string $url, array $data = []): string
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        if($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        if(curl_errno($ch)){
            throw new \Exception(curl_error($ch));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}