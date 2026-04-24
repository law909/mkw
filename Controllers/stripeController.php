<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use mkw\store;

class stripeController extends \mkwhelpers\Controller
{

    private function getStripeClient()
    {
        $apiKey = \mkw\store::getParameter(\mkw\consts::StripeAPIKey);
        return new \Stripe\StripeClient($apiKey);
    }

    /**
     * @param \Entities\Bizonylatfej $biz
     *
     * @return array
     */
    public function createPaymentIntent($biz)
    {
        $res = ['result' => false];
        try {
            $stripe = $this->getStripeClient();
            $currency = strtolower($biz->getValutanemnev());
            $amount = $biz->getFizetendo();

            // Stripe expects amount in smallest currency unit (cents, fillér)
            // For zero-decimal currencies like HUF, JPY no multiplication needed
            $zeroDecimalCurrencies = ['bif', 'clp', 'djf', 'gnf', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];
            if (in_array($currency, $zeroDecimalCurrencies)) {
                $amountInSmallest = (int)round($amount);
            } else {
                $amountInSmallest = (int)round($amount * 100);
            }

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInSmallest,
                'currency' => $currency,
                'metadata' => [
                    'order_id' => $biz->getId(),
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $biz->setStripepaymentintentid($paymentIntent->id);
            $this->getEm()->persist($biz);
            $this->getEm()->flush();

            $res = [
                'result' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ];
        } catch (\Exception $e) {
            \mkw\store::writelog('Stripe createPaymentIntent error: ' . $e->getMessage());
        }
        return $res;
    }

    public function webhook()
    {
        header('HTTP/1.1 200 OK');
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $webhookSecret = \mkw\store::getParameter(\mkw\consts::StripeWebhookSecret);

        try {
            if ($webhookSecret) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                $data = json_decode($payload, true);
                $event = \Stripe\Event::constructFrom($data);
            }
        } catch (\Exception $e) {
            \mkw\store::writelog('Stripe webhook error: ' . $e->getMessage());
            http_response_code(400);
            return;
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSuccess($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                \mkw\store::writelog('Stripe payment failed: ' . $paymentIntent->id);
                break;
        }
    }

    private function handlePaymentSuccess($paymentIntent)
    {
        /** @var \Entities\Bizonylatfej $megrendeles */
        $megrendeles = $this->getRepo(Bizonylatfej::class)->findOneBy(['stripepaymentintentid' => $paymentIntent->id]);
        if ($megrendeles) {
            $bizstatusz = $this->getRepo(Bizonylatstatusz::class)->find(
                \mkw\store::getParameter(\mkw\consts::StripeFizetveStatusz)
            );
            if ($bizstatusz) {
                $megrendeles->setSimpleedit(true);
                $megrendeles->setBizonylatstatusz($bizstatusz);
                $this->getEm()->persist($megrendeles);
                $this->getEm()->flush();
                $megrendeles->sendStatuszEmail($bizstatusz->getEmailtemplate());
            }
        }
    }

    public function paymentSuccess()
    {
        $paymentIntentId = $this->params->getStringRequestParam('payment_intent');
        if ($paymentIntentId) {
            try {
                $stripe = $this->getStripeClient();
                $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
                if ($paymentIntent->status === 'succeeded') {
                    $this->handlePaymentSuccess($paymentIntent);
                }
            } catch (\Exception $e) {
                \mkw\store::writelog('Stripe paymentSuccess error: ' . $e->getMessage());
            }
        }
        Header('Location: ' . \mkw\store::getRouter()->generate('checkoutkoszonjuk'));
    }
}
