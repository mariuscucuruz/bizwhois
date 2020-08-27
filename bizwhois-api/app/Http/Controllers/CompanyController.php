<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    private $apiUrl = '';
    private $apiKey = '';
    private $apiClient;
    private $apiLimits = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        if (!env('API_URL') || !env('API_KEY')) {
            abort(403, 'Missing API details.');
            //throw new \Exception('Missing API details.');
        }

        $this->apiUrl = env('API_URL');
        $this->apiKey = env('API_KEY');

        $this->apiClient = Http::withBasicAuth($this->apiKey, '');
    }

    /**
     * Route handle.
     *
     * @param Request $request
     *
     * @return array
     */
    public function findByName(Request $request, $companyName): array
    {
        $found = [];
        $response = $this->apiCall('search-company', urlencode($companyName));

        foreach ($response->json()['items'] as $item) {
            $found[] = [
                'company_name'   => $item['title'].' '.$item['company_type'].' '.$item['company_status'],
                'company_number' => $item['company_number'],
            ];
        }

        return $found;
    }

    /**
     * Route handle.
     *
     * @param Request $request
     *
     * @return array
     */
    public function findByOfficer(Request $request, $officerName): array
    {
        $response = $this->apiCall('search-officer', urlencode($officerName));

        return $response->json();
    }

    /**
     * Route handler.
     *
     * @param Request $request
     * @param int     $companyNumber
     *
     * @return array
     */
    public function findByNumber(Request $request, int $companyNumber): array
    {
        // prepend 0's to short numbers
        $companyNumber = str_pad($companyNumber, 8, 0, STR_PAD_LEFT);

        $details = false; //Company::findByNumber($companyNumber);

        if (!$details) {
            $apiResponse = $this->apiCall('get-company', $companyNumber);

            $details = $this->parseDetails($apiResponse->json());
        }

        return $details;
    }

    /**
     * @param $json
     *
     * @return mixed
     */
    private function parseDetails($json)
    {
        $companyHash = md5(json_encode($json));
        // save history

        return $json;
    }

    /**
     * Perform the actual API call and handle response.
     *
     * @param $operation
     * @param $payload
     *
     * @return mixed
     */
    private function apiCall($operation, $payload)
    {
        $response = null;

        switch ($operation) {
            case 'search-company':
                $response = $this->apiClient->get($this->apiUrl.'/search/companies/?q='.$payload);
                break;
            case 'search-officer':
                $response = $this->apiClient->get($this->apiUrl.'/search/officers/?q='.$payload);
                break;
            case 'get-company':
                $response = $this->apiClient->get($this->apiUrl.'/company/'.$payload);
                break;
        }

        return $this->handleResponse($response);
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    private function handleResponse($response)
    {
        $this->updateLimits($response->headers());

        return $response;
    }

    /**
     * Update API limits / quota after each request.
     *
     * @param array $headers
     *
     * @return |null
     */
    private function updateLimits(array $headers = [])
    {
        if (!count($headers) > 0) {
            return null;
        }

        $this->apiLimits = [
            'limit'  => $headers['X-Ratelimit-Limit'] ?? null,
            'remain' => $headers['X-Ratelimit-Remain'] ?? null,
            'reset'  => $headers['X-Ratelimit-Reset'] ?? null,
            'window' => $headers['X-Ratelimit-Window'] ?? null,
        ];
    }
}
