<?php declare(strict_types=1);

namespace Vnpay\SDK;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use OAuth2ClientCredentials\OAuthClient;
use Vnpay\SDK\Models\Payment;
use Exception;

class VnpayClient
{
    /**
     * @var OAuthClient
     */
    private $oauthClient;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @param string $apiUrl
     */
    public function __construct($apiUrl)
    {
        $this->oauthClient = new OAuthClient(
            config('vnpay.oauth.url'),
            config('vnpay.oauth.client_id'),
            config('vnpay.oauth.client_secret')
        );
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param callable $handler
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function request($handler)
    {
        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->oauthClient->getAccessToken(),
        ])
            ->withoutVerifying();

        $response = $handler($request);

        if ($response->status() == 401) {
            $this->oauthClient->getAccessToken(true);
        }

        return $response;
    }

    /**
     * @param string $route
     * @return string
     */
    private function getUrl($route)
    {
        return $this->apiUrl . '/api/client/v1' . $route;
    }

    /**
     * @param int $amount
     * @param string $orderInfo
     * @param string $returnUrl
     * @param string $ipnUrl
     * @param string $orderId
     * @param string $ipAddress
     * @param string $locale
     * @return false|Payment
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function create($amount, $orderInfo, $returnUrl, $ipnUrl, $orderId, $ipAddress, $locale)
    {
        $params = [
            'amount' => $amount,
            'order_info' => $orderInfo,
            'return_url' => $returnUrl,
            'ipn_url' => $ipnUrl,
            'order_id' => $orderId,
            'ip_address' => $ipAddress,
            'locale' => $locale,
        ];

        $response = $this->request(function (PendingRequest $request) use ($params) {
            return $request->asJson()
                ->post($this->getUrl('/payment/create'), $params);
        });

        if (!$response->successful()) {
            return false;
        }

        return Payment::fromArray($response->json());
    }

    /**
     * @param array $params
     * @return \Vnpay\SDK\Models\Payment
     * @throws \Exception
     */
    public function ipn($params)
    {
        if (empty($params)) {
            throw new Exception('Empty params');
        }

        return Payment::fromArray($params);
    }
}
