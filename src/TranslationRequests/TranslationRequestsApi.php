<?php

namespace Smartling\TranslationRequests;

use Psr\Log\LoggerInterface;
use Smartling\AuthApi\AuthApiInterface;
use Smartling\BaseApiAbstract;
use Smartling\Exceptions\SmartlingApiException;
use Smartling\TranslationRequests\Params\CreateTranslationRequestParams;
use Smartling\TranslationRequests\Params\SearchTranslationRequestParams;
use Smartling\TranslationRequests\Params\UpdateTranslationRequestParams;

/**
 * Class TranslationRequestsApi
 * @package Smartling\TranslationRequests
 */
class TranslationRequestsApi extends BaseApiAbstract
{
    const ENDPOINT_URL = 'https://api.smartling.com/submission-service-api/v2/projects';

    /**
     * @param AuthApiInterface $authProvider
     * @param string $projectId
     * @param LoggerInterface $logger
     *
     * @return TranslationRequestsApi
     */
    public static function create(AuthApiInterface $authProvider, $projectId, $logger = null)
    {
        $client = self::initializeHttpClient(self::ENDPOINT_URL);

        $instance = new self($projectId, $client, $logger, self::ENDPOINT_URL);
        $instance->setAuth($authProvider);

        return $instance;
    }

    /**
     * @param string $bucketName
     * @param CreateTranslationRequestParams $params
     * @return mixed
     * @throws SmartlingApiException
     */
    public function createTranslationRequest($bucketName, CreateTranslationRequestParams $params)
    {
        $requestData = $this->getDefaultRequestData('json', $params->exportToArray());
        $requestUri = vsprintf('buckets/%s/submissions', [$bucketName]);
        return $this->sendRequest($requestUri, $requestData, self::HTTP_METHOD_POST);
    }


    /**
     * @param string $bucketName
     * @param string $submissionUid
     * @return array
     * @throws SmartlingApiException
     */
    public function getTranslationRequest($bucketName, $submissionUid)
    {
        $requestData = $this->getDefaultRequestData('query', []);
        $requestUri = vsprintf('buckets/%s/submissions/%s', [$bucketName, $submissionUid]);
        return $this->sendRequest($requestUri, $requestData, self::HTTP_METHOD_GET);
    }

    /**
     * @param string $bucketName
     * @param string $submissionUid
     * @param UpdateTranslationRequestParams $params
     * @return mixed
     * @throws SmartlingApiException
     */
    public function updateTranslationRequest($bucketName, $submissionUid, UpdateTranslationRequestParams $params)
    {
        $requestData = $this->getDefaultRequestData('json', $params->exportToArray());
        $requestUri = vsprintf('buckets/%s/submissions/%s', [$bucketName, $submissionUid]);
        return $this->sendRequest($requestUri, $requestData, self::HTTP_METHOD_PUT);
    }

    /**
     * @param string $bucketName
     * @param SearchTranslationRequestParams $searchParams
     * @return array
     * @throws SmartlingApiException
     */
    public function searchTranslationRequests($bucketName, SearchTranslationRequestParams $searchParams)
    {
        $requestData = $this->getDefaultRequestData('query', $searchParams->exportToArray());
        $requestUri = vsprintf('buckets/%s/submissions', [$bucketName]);
        return $this->sendRequest($requestUri, $requestData, self::HTTP_METHOD_GET);
    }
}