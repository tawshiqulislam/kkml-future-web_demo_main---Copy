<?php

namespace App\Http\Controllers;

use App\CustomerPackage;
use App\Exceptions\sManagerPaymentException;
use App\Order;
use App\Utility\sManagerPaymentService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View as ViewClass;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Session;

class sManagerPaymentController extends Controller
{

    public function pay()
    {
        try {
            //dd(session()->all());

            $amount = 0;
            if (Session::has('payment_type')) {
                if (Session::get('payment_type') == 'cart_payment') {
                    $order = Order::findOrFail(Session::get('order_id'));
                    $amount = round($order->grand_total);
                } elseif (Session::get('payment_type') == 'wallet_payment') {
                    $amount = round(Session::get('payment_data')['amount']);
                } elseif (Session::get('payment_type') == 'customer_package_payment') {
                    $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                    $amount = round($customer_package->amount);
                } elseif (Session::get('payment_type') == 'seller_package_payment') {
                    $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                    $amount = round($seller_package->amount);
                }
            }

            $shipping_info = Session::get('shipping_info');


            $name = $shipping_info['name'] ?? '';
            $phone = $shipping_info['phone'] ?? '';
            $email = $shipping_info['email'] ?? '';
            $address = $shipping_info['address'] ?? '';
            $address .= $shipping_info['city'] ?? '';
            $trnxId = 'trnx_' . Str::uuid();


            $info = [
                // 'amount' => $amount,
                // 'transaction_id' => $trnxId,
                // 'success_url' => route('smanager.success', ['trnxId' => $trnxId]),
                // 'fail_url' => route('smanager.fail'),
                // 'customer_name' => $name,
                // 'customer_mobile' => $phone,
                // 'purpose' => 'Khoshrozltd.com Payment',
                // 'payment_details' => 'Thank you for Payment'
            ];
            $paymentService = new sManagerPaymentService();
            $link = $paymentService->initiatePayment($info);

            return redirect($link);

        } catch (ValidationException $ex) {


            return Redirect::back()
                ->withErrors($ex->errors())
                ->withInput();
        } catch (QueryException | sManagerPaymentException | Exception $ex) {
            flash(translate($ex->getMessage()))->error();
            return Redirect::back()->withErrors([$ex->getMessage()]);
        }
    }


    public function success($trnxId = '')
    {
        $paymentService = new sManagerPaymentService();

        try {
            if (!$trnxId) {
                if (!ViewClass::exists('failed')) {
                    throw new InvalidArgumentException('View file "failed" not found.');
                }
            }

            if (!$paymentService->trnxDetails($trnxId)) {
                if (!ViewClass::exists('failed')) {
                    throw new InvalidArgumentException('View file "failed" not found.');
                }
            }

            if (!ViewClass::exists('success')) {
                throw new InvalidArgumentException('View file "success" not found.');
            }

            $payment_type = Session::get('payment_type');

            if ($payment_type == 'cart_payment') {
                $checkoutController = new CheckoutController;
                return $checkoutController->checkout_done(Session::get('order_id'), $request->payment_details);
            }

            if ($payment_type == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done(Session::get('payment_data'), $request->payment_details);
            }

            if ($payment_type == 'customer_package_payment') {
                $customer_package_controller = new CustomerPackageController;
                return $customer_package_controller->purchase_payment_done(Session::get('payment_data'), $request->payment_details);
            }
            if ($payment_type == 'seller_package_payment') {
                $seller_package_controller = new SellerPackageController;
                return $seller_package_controller->purchase_payment_done(Session::get('payment_data'), $request->payment_details);
            }

            flash(translate('Order has been placed successfully'))->success();
            return redirect()->route('home');
        } catch (InvalidArgumentException | Exception $ex) {
            echo $ex->getMessage() . 'on line ' . $ex->getLine();
        }
    }


    public function fail(): View
    {
        flash(translate('Payment is Completed!'))->error();
        return redirect()->route('home');
    }
}
