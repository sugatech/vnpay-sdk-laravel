<?php declare(strict_types=1);

namespace Vnpay\SDK;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use InvalidArgumentException;

class VnpayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('vnpay.client', function ($app) {
            $options = $app['config']->get('vnpay');

            if (!isset($options['api_url'])) {
                throw new InvalidArgumentException('Not found api_url config');
            }

            if (!isset($options['oauth']['url'])) {
                throw new InvalidArgumentException('Not found oauth.url config');
            }

            if (!isset($options['oauth']['client_id'])) {
                throw new InvalidArgumentException('Not found oauth.client_id config');
            }

            if (!isset($options['oauth']['client_secret'])) {
                throw new InvalidArgumentException('Not found oauth.client_secret config');
            }

            return new VnpayClient($options['api_url']);
        });
    }

    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('vnpay.php')], 'vnpay');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('vnpay');
        }
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/vnpay.php';
    }
}
