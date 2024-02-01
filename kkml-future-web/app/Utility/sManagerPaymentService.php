<?php

namespace App\Utility;

use App\Exceptions\sManagerPaymentException;
use Illuminate\Support\Facades\Http;


class sManagerPaymentService
{
    public function validateInsertData($request)
    {
        # Input Validation
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|min:11|regex:/\+?(88)?0?1[3456789][0-9]{8}\b/',
            'address' => 'nullable',
            'amount' => 'required|numeric|gte:10|lt:500000'
        ];

        $messages = [
            'name.required' => 'Please provide your name.',
            'email.email' => 'Please provide a valid Email Address.',
            'phone.required' => 'Please provide your Phone Number.',
            'phone.min' => 'Please provide your Phone Number.',
            'phone.regex' => 'Please provide a valid Bangladeshi Phone Number',
            'amount.required' => 'Please provide amount.',
            'amount.numeric' => 'Please provide a valid amount.',
            'amount.gte' => 'Amount must be greater than or equal 10.',
            'amount.lt' => 'Amount must be less than 500000'
        ];

        $request->validate($rules, $messages);
    }


    /**
     * @param $data
     * @return string
     * @throws sManagerPaymentException
     */
    public function initiatePayment($data): string
    {
        $url = env('SMANAGER_URL') . '/v1/ecom-payment/initiate';

//        $responseJSON = Http::withHeaders([
//            'client-id' => env('SMANAGER_CLIENT_ID'),
//            'client-secret' => env('SMANAGER_CLIENT_SECRET')
//        ])->get($url)->object();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'client-id: ' . env('SMANAGER_CLIENT_ID'),
                'client-secret: ' . env('SMANAGER_CLIENT_SECRET'),
                'Accept: application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);


        if ($response['code'] !== 200) {
            throw new sManagerPaymentException($response['message']);
        }


        return $response['data']['link'];
    }

    /**
     * @param $trnxId
     * @return bool
     * @throws sManagerPaymentException
     */
    public function trnxDetails($trnxId): bool
    {
        $url = env('SMANAGER_URL') . '/v1/ecom-payment/details';

        $responseJSON = Http::withHeaders([
            'client-id' => env('SMANAGER_CLIENT_ID'),
            'client-secret' => env('SMANAGER_CLIENT_SECRET')
        ])->get($url, [
            'transaction_id' => $trnxId,
        ])->object();

        $code = $responseJSON->code;
        $message = $responseJSON->message;

        if ($code !== 200) {
            throw new sManagerPaymentException($message);
        }

        # Check if the transaction is completed
        $paymentStatusFromApi = $responseJSON->data->payment_status;
        if ($paymentStatusFromApi !== 'completed') {
            return false;
        }

        return true;
    }

}
