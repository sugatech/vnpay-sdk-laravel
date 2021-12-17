<?php declare(strict_types=1);

namespace Vnpay\SDK\Models;

use Vnpay\SDK\Enums\PaymentStatus;

class Payment
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $redirectUrl;

    /**
     * @var string
     */
    public $ipnUrl;

    /**
     * @var string
     */
    public $transactionId;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $updatedAt;

    public function __construct($id, $orderId, $amount, $status, $redirectUrl, $ipnUrl, $transactionId, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->status = $status;
        $this->redirectUrl = $redirectUrl;
        $this->ipnUrl = $ipnUrl;
        $this->transactionId = $transactionId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param $array
     * @return \Vnpay\SDK\Models\Payment
     */
    static function fromArray($array)
    {
        return new Payment(
            @$array['id'],
            @$array['order_id'],
            @$array['amount'],
            @$array['status'],
            @$array['redirect_url'],
            @$array['ipn_url'],
            @$array['transaction_id'],
            @$array['created_at'],
            @$array['updated_at'],
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'status' => $this->status,
            'redirect_url' => $this->redirectUrl,
            'ipn_url' => $this->ipnUrl,
            'transaction_id' => $this->transactionId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    /**
     * @return bool
     */
    public function isCaptured()
    {
        return $this->status == PaymentStatus::Captured;
    }
}
