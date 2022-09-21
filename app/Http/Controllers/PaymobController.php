<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;


class PaymobController
{
    public $name = 'Paymob Checkout';
    protected $id = 'paymob_checkout';
    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type' => 'checkbox',
                'id' => 'enable',
                'label' => __('Enable Paymob?')
            ],
            [
                'type' => 'input',
                'id' => 'name',
                'label' => __('Custom Name'),
                'std' => __('Paymob'),
                'multi_lang' => '1'
            ],
            [
                'type' => 'upload',
                'id' => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type' => 'editor',
                'id' => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => '1'
            ],
            [
                'type' => 'input',
                'id' => 'stripe_secret_key',
                'label' => __('Secret Key'),
            ],
            [
                'type' => 'input',
                'id' => 'stripe_publishable_key',
                'label' => __('Publishable Key'),
            ],
            [
                'type' => 'checkbox',
                'id' => 'stripe_enable_sandbox',
                'label' => __('Enable Sandbox Mode'),
            ],
            [
                'type' => 'input',
                'id' => 'stripe_test_secret_key',
                'label' => __('Test Secret Key'),
            ],
            [
                'type' => 'input',
                'id' => 'stripe_test_publishable_key',
                'label' => __('Test Publishable Key'),
            ],
            [
                'type' => 'input',
                'id' => 'endpoint_secret',
                'label' => __('Webhook Secret'),
                'desc' => __('Webhook url: <code>:code</code>', ['code' => 200]),
            ]
        ];
    }


    public function process(Request $request, $booking, $service)
    {
        if (in_array($booking->status, [
            'PAID',
            'COMPLETED',
            'CANCELLED'
        ])) {

            throw new Exception(__('Booking status does need to be paid'));
        }
        if (!$booking->pay_now) {
            throw new Exception(__('Booking total is zero. Can not process payment gateway!'));
        }
        $payment = new Payment();

        if ($booking->paid <= 0) {
            //line 95
            $booking->status = $booking::UNPAID;
            $booking->paid = 0;
        } else {
            if ($booking->paid < $booking->total) {
                $booking->status = $booking::PARTIAL_PAYMENT;
            } else {
                $booking->status = $booking::PAID;
            }
            $booking->paid = (float)$booking->pay_now;
        }
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = 'draft';
        $payment->amount = (float)$booking->pay_now;
        $payment->save();
        // dd($payment->toArray());
        $response = $this->processToken($payment, $booking);
        /*         dd($response);*/
        if ($response && !$response['error']) {
            // $booking->status = $booking::UNPAID;
            $booking->payment_id = $payment->id;
            $booking->save();
            // redirect to offsite payment gateway
            if ($booking->status != $booking::UNPAID) {
                Mail::to($booking->email)->send(new NewBookingEmail($booking, 'customer'));
            }
            response()->json([
                'url' => $response['redirect']
            ])->send();
        } else {
            throw new Exception('Paymob Gateway: ' . $response['message']);
        }
    }


    public function processToken()
    {
        $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
        // $client = new \GuzzleHttp\Client();
        // dd($client);
        $url1 = 'https://accept.paymobsolutions.com/api/auth/tokens';
        $url2 = 'https://accept.paymobsolutions.com/api/ecommerce/orders';
        $url3 = 'https://accept.paymobsolutions.com/api/acceptance/payment_keys';
        $apiKey = 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndjbTltYVd4bFgzQnJJam96TmprMGZRLlZWSVFvc2dMeVpfWDczcENtbGU2VU1ZMzVKX1pTU2tfc3J0WGI4S0Zncl83NlhGNHRCZVVDVnBGLVVYVzJVeUltb1dxay1pMUZUR1AzS3RkLURRUUxn';
        $integration_id = 5832;
        //$integration_id  live = 2324007;
        $iframe_id = 381811;

        $otherData['integration_id'] = $integration_id;
        $otherData['iframe_id'] = $iframe_id;

        $response1 = $client->post($url1,
            ['body' => json_encode(
                [
                    'api_key' => $apiKey
                ]
            )]
        );
        // dd($response1->getStatusCode(),json_decode($response1->getBody()->getContents(),true));
        $redirect = '';
        $message = '';
        $error = true;
        $status = 'failed';
        $order_amount = 1000;
        // $order_amount = $payment->amount;
        $o_id = rand(1,999);
        // $o_id = $payment->booking_id;
        if ($response1->getStatusCode() == 201 && !empty($response1->getBody())) {
            $responseData = json_decode($response1->getBody()->getContents(), true);
            // dd($responseData);
            if (!empty($responseData['token']) && !empty($responseData['profile']) && !empty($responseData['profile']['id'])) {
                // $error = false;
                $token = $responseData['token'];
                $merchant_id = $responseData['profile']['id'];
                $total = ($order_amount * 100);
                $currency = 'EGP';
                // dd($currency);
                $step2Body = ['auth_token' => $token, 'delivery_needed' => false, 'merchant_id' => $merchant_id, 'amount_cents' => $total, 'currency' => $currency, 'merchant_order_id' => $o_id];


                $response2 = $client->post($url2,
                    ['body' => json_encode(
                        $step2Body
                    )]
                );

                if ($response2->getStatusCode() == 201 && !empty($response2->getBody())) {
                    $responseData2 = json_decode($response2->getBody()->getContents(), true);
                    $step2OrderId = $responseData2['id'];
                    $otherData['order_id'] = $step2OrderId;
                    $order['transaction_ref'] = $step2OrderId;

                    $billingData = [
                        'first_name' => $booking->first_name ?? 'test',
                        'last_name' => $booking->last_name ?? 'test',
                        'city' => $booking->city ?? 'test',
                        'state' => $booking->city ?? 'test',
                        'country' =>'test',
                        'email' => $booking->email ?? 'test@yahoo.com',
                        'street' => $booking->city ?? 'test',
                        'phone_number' => $booking->phone ?? 'test',
                        'building' => $booking->city ?? 'test',
                        'floor' => $booking->city ?? 'test',
                        'apartment' => $booking->city ?? 'test'
                    ];

                    $step3Body = ['auth_token' => $token, 'amount_cents' => $total, 'expiration' => 3600, 'order_id' => $step2OrderId, 'currency' => $currency, 'integration_id' => $integration_id, 'lock_order_when_paid' => true, 'billing_data' => $billingData];


                    $response3 = $client->post($url3,
                        ['body' => json_encode(
                            $step3Body
                        )]
                    );

                    if ($response3->getStatusCode() == 201 && !empty($response3->getBody())) {
                        $step3Response = json_decode($response3->getBody()->getContents(), true);

                        $finalToken = $step3Response['token'];
                        $otherData['final_token'] = $finalToken;
                   /*     $payment->payment_object = json_encode($otherData) ?? '';
                        $payment->save();*/
                        $error = false;
                        $status = 'draft';
                        $redirect = "https://accept.paymobsolutions.com/api/acceptance/iframes/$iframe_id?payment_token=$finalToken";
                    } else {
                        $message = 'Error in gateway 1';
                    }
                    // dd($response3->json());
                } else {
                    $message = 'Error in gateway 2';
                }
            } else {
                $message = 'Error in gateway 3';
            }
        } else {
            $error = true;
            $message = 'Error in gateway 4';
        }
        return ['message' => $message, 'error' => $error, 'redirect' => $redirect];
    }

    public function cancelNormalPayment()
    {
        $c = $request->query('merchant_order_id');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();
            }

            // Refund without check status
            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with('error', __('You cancelled the payment'));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }

    public function cancelPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();
            }

            // Refund without check status
            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with('error', __('You cancelled the payment'));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }

    public function getPublicKey()
    {
        if ($this->getOption('stripe_enable_sandbox')) {
            return $this->getOption('stripe_test_publishable_key');
        }
        return $this->getOption('stripe_public_key');
    }

    public function callbackPayment(Request $request)
    {
        return $this->callback($request);
    }

    public function callback(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $array2 = json_decode($payload, true);
        $event = NULL;
        Log::debug('PAYMOB RESPONSE 1: ', json_decode($payload, true));
        if (!empty($array2) && !empty($array2['obj']['order']['merchant_order_id']) && !empty($array2['obj']['success']) && $array2['obj']['success'] == true) {
            $order_id = $array2['obj']['order']['merchant_order_id'];
            $booking = Booking::where('id', $order_id)->first();
            if (!empty($booking)) {
                $payment = $booking->payment;
                if (!empty($payment)) {
                    $payment->markAsCompleted($array2);
                    if ($booking->paid <= 0) {
                        $booking->status = $booking::PROCESSING;
                    } else {
                        if ($booking->paid < $booking->total) {
                            $booking->status = $booking::PARTIAL_PAYMENT;
                        } else {
                            $booking->status = $booking::PAID;
                        }
                    }
                    // $payment->status = 'paid';
                    // $booking->status = $booking::PAID;
                    $booking->save();
                    $payment->save();
                }
            }

        }
        // Log::debug("PAYMOB RESPONSE 2: ",$request->all());
    }

    public function processNormal($payment)
    {
        $this->setupStripe();
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'success_url' => $this->getReturnUrl(true) . '?pid=' . $payment->code . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->getCancelUrl(true) . '?pid=' . $payment->code,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => setting_item('currency_main'),
                        'unit_amount' => (float)$payment->amount * 100,
                        'product_data' => [
                            'name' => __('Buy credits'),
                        ],
                    ],

                    'quantity' => 1
                ],
            ]
        ]);
        $payment->addMeta('stripe_session_id', $session->id);
        if (!empty($session->url)) {
            return [true, false, $session->url];
        }
        return [true];
    }

    public function setupStripe()
    {
        \Stripe\Stripe::setApiKey($this->getSecretKey());
    }

    public function getSecretKey()
    {
        if ($this->getOption('stripe_enable_sandbox')) {
            return $this->getOption('stripe_test_secret_key');
        }
        return $this->getOption('stripe_secret_key');
    }

    public function confirmNormalPayment()
    {
        /**
         * @var Payment $payment
         */
        $request = \request();
        Log::debug('PAYMOB REDIRECT RESPONSE: ', $request->all());
        // dd($request->all());
        $c = $request->query('merchant_order_id');
        $booking = Booking::where('id', $c)->first();
        // $payment = Payment::where('code', $c)->first();


        // dd(34,$c,$booking->toArray());
        if (!empty($booking)) {
            $payment = $booking->payment;
            if (in_array($booking->status, [$booking::UNPAID]) && !empty($payment) and in_array($payment->status, ['draft'])) {
                // dd((int)$request->success,(boolean)$request->success == false ? 85 : 95);
                if (!$request->success || $request->success === 'false') {
                    // dd(34);
                    $payment->status = 'cancel';
                    $payment->save();
                    // dd(85);
                    return [false, __('Your payment has been canceled'), $booking->getDetailUrl()];
                } else {
                    $payment->status = 'paid';
                    $booking->status = $booking::PAID;
                    $booking->save();
                    $payment->save();
                    return [true, __('Your payment has been completed'), $booking->getDetailUrl()];
                }
            }
            return [true, __('Your payment has been completed'), $booking->getDetailUrl()];
        }
        return [false];
    }


}
