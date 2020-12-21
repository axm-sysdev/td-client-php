<?php

namespace AXM\TD;

use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var string
     */
    protected $endpoint = 'https://api.treasuredata.co.jp';

    /**
     * TDClient constructor.
     *
     * @param string $apiKey
     * @param array $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->endpoint = $options['endpoint'] ?? $this->endpoint;
        $this->client = new HttpClient($options['client_config'] ?? []);
    }

    /**
     * issue query.
     * https://tddocs.atlassian.net/wiki/spaces/PD/pages/1085354/REST+APIs+in+Treasure+Data#job%2Fstatus%2F%3Ajob_id
     *
     * @param string $db
     * @param string $type
     * @param string $query
     * @param array $params
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query(string $db, string $type, string $query, array $params = []): string
    {
        $options = $this->createRequestOptions();
        $params['query'] = $query;
        $options['form_params'] = $params;

        $result = $this->client->request('POST', sprintf('/v3/job/issue/%s/%s', $type, $db), $options);
        $contents = json_decode($result->getBody()->getContents(), true);
        return $contents['job_id'];
    }

    /**
     * issue hive query.
     *
     * @param string $db
     * @param string $query
     * @param array $params
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function hiveQuery(string $db, string $query, array $params = []): string
    {
        return $this->query($db, 'hive', $query, $params);
    }

    /**
     * issue presto query.
     *
     * @param string $db
     * @param string $query
     * @param array $params
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function prestoQuery(string $db, string $query, array $params = []): string
    {
        return $this->query($db, 'presto', $query, $params);
    }

    /**
     * shows the status of a specific job
     * https://tddocs.atlassian.net/wiki/spaces/PD/pages/1085354/REST+APIs+in+Treasure+Data#job%2Fstatus%2F%3Ajob_id
     *
     * @param string $jobId
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function jobStatus(string $jobId): string
    {
        $options = $this->createRequestOptions();
        $result = $this->client->request('GET', sprintf('/v3/job/status/%s', $jobId), $options);
        $contents = json_decode($result->getBody()->getContents(), true);
        return $contents['status'];
    }

    /**
     * returns the result of a specific job
     * https://tddocs.atlassian.net/wiki/spaces/PD/pages/1085354/REST+APIs+in+Treasure+Data#job%2Fresult%2F%3Ajob_id%3Fformat%3Dmsgpack.gz
     *
     * @param string $jobId
     * @param string $format
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function jobResult(string $jobId, string $format = 'json')
    {
        $options = $this->createRequestOptions();
        $result = $this->client->request('GET', sprintf('/v3/job/result/%s?format=%s', $jobId, $format), $options);
        return $result->getBody()->getContents();
    }

    /**
     * @return array
     */
    protected function createRequestOptions(): array
    {
        return [
            'base_uri' => $this->endpoint,
            'headers' => [
                'Authorization' => sprintf('TD1 %s', $this->apiKey)
            ]
        ];
    }
}
