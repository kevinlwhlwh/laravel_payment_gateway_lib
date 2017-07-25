<?php
namespace App\Http\Controllers;

/** Basic class **/
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

/** Paypal class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Api\PaymentCard;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalConfigurationException;
use PayPal\Exception\PayPalMissingCredentialException;
use PayPal\Exception\PayPalInvalidCredentialException;


class PayPalController extends Controller
{

    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** setup PayPal api context **/
        $paypal_conf = config('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    /**
     * Show the application paywith paypalpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithPaypal()
    {
        return view('payment_application/make_payment');
    }

    /**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPaymentWithpaypal(Request $request)
    {

        $this->validate($request, [
            'cardType' => 'required|max:255',
            'cardNumber' => 'required|alpha_num',
            'expireMonth' => 'required|alpha_num',
            'expireYear' => 'required|alpha_num|after:last year',
            'cardCvv2' => 'required|alpha_num|min:3|max:4',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'currency' => 'required|string|min:3|max:3',
            'amount' => 'required|alpha_num',
            'customerName' => 'required|string',
            'customerPhone' => 'required|string',
        ]);

        $card = new PaymentCard();
        $card->setType($request->cardType)
            ->setNumber($request->cardNumber)
            ->setExpireMonth($request->expireMonth)
            ->setExpireYear($request->expireYear)
            ->setCvv2($request->cardCvv2)
            ->setFirstName($request->firstName)
            ->setLastName($request->lastName)
            ->setBillingCountry("HK");

        $fi = new FundingInstrument();
        $fi->setPaymentCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        $item1 = new Item();
        $item1->setName('Three Dollar Kevin 40 oz')
            ->setDescription('Three Dollar Kevin 40 oz')
            ->setCurrency($request->currency)
            ->setQuantity(1)
            ->setTax(0)
            ->setPrice($request->amount);


        $itemList = new ItemList();
        $itemList->setItems(array($item1));


        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($request->amount);

        $amount = new Amount();
        $amount->setCurrency($request->currency)
            ->setTotal($request->amount)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Tools Kevin Pay by PayPal")
            ->setInvoiceNumber(uniqid());

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

//        $request = clone $payment;



        try {
            $payment->create($this->_api_context);
//            echo "<pre>";
//            printf($payment);
//            echo "</pre>";
//            exit(1);
        } catch (PayPalConnectionException $ex) {

            if (config('app.debug')) {
                Session::put('error','Connection timeout');
                return Redirect::route('paywithpaypal');
            } else {
                Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');
            }

        } catch (PayPalConfigurationException $ex) {

            Session::put('error','PayPalConfigurationException');
            return Redirect::route('paywithpaypal');

        } catch (PayPalMissingCredentialException $ex ) {

            Session::put('error','PayPalMissingCredentialException');
            return Redirect::route('paywithpaypal');

        } catch (PayPalInvalidCredentialException $ex) {

            Session::put('error','PayPalInvalidCredentialException');
            return Redirect::route('paywithpaypal');

        }

        if($payment->getState() == 'approved'){
            Session::put('paypal_payment_id', $payment->getId());
            Session::put($payment->getId(),collect([
                'customer_name' => $request->customerName,
                'customer_Phone' => $request->customerPhone,
                'currency' => $request->currency,
                'amount' => $request->amount,
            ]));
            return Redirect::route('paywithpaypal')->with(['success' => 'Payment success! Reference ID:'.$payment->getId()]);
        }

        Session::put('error','Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }

    public function getPaymentStatus()
    {
        $payment_id = Session::get('paypal_payment_id');

        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            Session::put('error','Payment failed');
            return Redirect::route('paywithpaypal');
        }
        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            Session::put('success','Payment success');
            return Redirect::route('paywithpaypal');
        }

        Session::put('error','Payment failed');

        return Redirect::route('paywithpaypal');
    }

    public function checkPaymentRecord(){
        return view('payment_application/check_payment');
    }

    public function getPaymentRecord(Request $request){


        try {
            $payment = Payment::get($request->refNumber, $this->_api_context);

        } catch (PayPalConfigurationException $ex) {

//            Session::put('error','PayPalConfigurationException');
            Session::put('error', 'Payment ID: ['.$request->refNumber.'] record not found');
            return Redirect::route('checkPayment');

        } catch (PayPalMissingCredentialException $ex ) {

//            Session::put('error','PayPalMissingCredentialException');
            Session::put('error', 'Payment ID: ['.$request->refNumber.'] record not found');

            return Redirect::route('checkPayment');

        } catch (PayPalInvalidCredentialException $ex) {

//            Session::put('error','PayPalInvalidCredentialException');
            Session::put('error', 'Payment ID: ['.$request->refNumber.'] record not found');
            return Redirect::route('checkPayment');

        }catch (PayPalConnectionException $ex) {

//            Session::put('error','PayPalConnectionException');
            Session::put('error', 'Payment ID: ['.$request->refNumber.'] record not found');
            return Redirect::route('checkPayment');

        }

        if($payment->getState() == 'approved'){
            return Redirect::route('checkPayment')->with(['success' => 'Payment approved!', 'payment_details' => session($payment->getId())->all()]);;
        }else{
            Session::put('error', 'Payment ID: ['.$request->refNumber.'] record not found');
            return Redirect::route('checkPayment');
        }


    }
}