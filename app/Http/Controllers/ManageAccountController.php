<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountPaymentGateway;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Models\Timezone;
use App\Models\User;
use Auth;
use Hash;
use HttpClient;
use Illuminate\Http\Request;
use Input;
use Mail;
use Validator;
use GuzzleHttp\Client;

class ManageAccountController extends MyBaseController
{
    /**
     * Show the account modal
     *
     * @param Request $request
     * @return mixed
     */
    public function showEditAccount(Request $request)
    {
        $data = [
            'account'                  => Account::find(Auth::user()->account_id),
            'timezones'                => Timezone::pluck('location', 'id'),
            'currencies'               => Currency::pluck('title', 'id'),
            'payment_gateways'         => PaymentGateway::pluck('provider_name', 'id'),
            'account_payment_gateways' => AccountPaymentGateway::scope()->get(),
            'version_info'             => $this->getVersionInfo(),
        ];

        return view('ManageAccount.Modals.EditAccount', $data);
    }


    public function showStripeReturn()
    {
        $error_message = trans("Controllers.stripe_error");

        if (Input::get('error') || !Input::get('code')) {
            \Session::flash('message', $error_message);

            return redirect()->route('showEventsDashboard');
        }

        $request = [
            'url'    => 'https://connect.stripe.com/oauth/token',
            'params' => [

                'client_secret' => STRIPE_SECRET_KEY,
                'code'          => Input::get('code'),
                'grant_type'    => 'authorization_code',
            ],
        ];

        $response = HttpClient::post($request);

        $content = $response->json();

        if (isset($content->error) || !isset($content->access_token)) {
            \Session::flash('message', $error_message);

            return redirect()->route('showEventsDashboard');
        }

        $account = Account::find(\Auth::user()->account_id);

        $account->stripe_access_token = $content->access_token;
        $account->stripe_refresh_token = $content->refresh_token;
        $account->stripe_publishable_key = $content->stripe_publishable_key;
        $account->stripe_data_raw = json_encode($content);

        $account->save();

        \Session::flash('message', trans("Controllers.stripe_success"));

        return redirect()->route('showEventsDashboard');
    }


    /**
     * Edit an account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditAccount()
    {
        $account = Account::find(Auth::user()->account_id);

        if (!$account->validate(Input::all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $account->errors(),
            ]);
        }

        $account->first_name = Input::get('first_name');
        $account->last_name = Input::get('last_name');
        $account->email = Input::get('email');
        $account->enveloppe = Input::get('enveloppe');
        $account->timezone_id = Input::get('timezone_id');
        $account->currency_id = Input::get('currency_id');
        $account->save();

        return response()->json([
            'status'  => 'success',
            'id'      => $account->id,
            'message' => trans("Controllers.account_successfully_updated"),
        ]);
    }

    /**
     * Save account payment information
     *
     * @param Request $request
     * @return mixed
     */
    public function postEditAccountPayment(Request $request)
    {
        $account = Account::find(Auth::user()->account_id);
        $gateway_id = $request->get('payment_gateway_id');

        switch ($gateway_id) {
            case config('attendize.payment_gateway_stripe') : //Stripe
                $config = $request->get('stripe');
                break;
            case config('attendize.payment_gateway_paypal') : //PayPal
                $config = $request->get('paypal');
                break;
        }

        $account_payment_gateway = AccountPaymentGateway::firstOrNew(
            [
                'payment_gateway_id' => $gateway_id,
                'account_id'         => $account->id,
            ]);

        $account_payment_gateway->config = $config;
        $account_payment_gateway->account_id = $account->id;
        $account_payment_gateway->payment_gateway_id = $gateway_id;
        $account_payment_gateway->save();

        $account->payment_gateway_id = $gateway_id;
        $account->save();

        return response()->json([
            'status'  => 'success',
            'id'      => $account_payment_gateway->id,
            'message' => trans("Controllers.payment_information_successfully_updated"),
        ]);
    }

    /**
     * Invite a user to the application
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postInviteUser()
    {
        $rules = [
            'email' => ['required', 'email', 'unique:users,email,NULL,id,account_id,' . Auth::user()->account_id],
        ];

        $messages = [
            'email.email'    => trans("Controllers.error.email.email"),
            'email.required' => trans("Controllers.error.email.required"),
            'email.unique'   => trans("Controllers.error.email.unique"),
        ];

        $validation = Validator::make(Input::all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validation->messages()->toArray(),
            ]);
        }

        $temp_password = str_random(8);

        $user = new User();

        $user->email = Input::get('email');
        $user->password = Hash::make($temp_password);
        $user->account_id = Auth::user()->account_id;

        $user->save();

        $data = [
            'user'          => $user,
            'temp_password' => $temp_password,
            'inviter'       => Auth::user(),
        ];

        Mail::send('Emails.inviteUser', $data, function ($message) use ($data) {
            $message->to($data['user']->email)
                ->subject(trans("Email.invite_user", ["name"=>$data['inviter']->first_name . ' ' . $data['inviter']->last_name, "app"=>config('attendize.app_name')]));
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.success_name_has_received_instruction", ["name"=>$user->email]),
        ]);
    }

    public function getVersionInfo()
    {
        $installedVersion = null;
        $latestVersion = null;

        try {
            $http_client = new Client();

            $response = $http_client->get('https://attendize.com/version.php');
            $latestVersion = (string)$response->getBody();
            $installedVersion = file_get_contents(base_path('VERSION'));
        } catch (\Exception $exception) {
            return false;
        }

        if ($installedVersion && $latestVersion) {
            return [
                'latest'      => $latestVersion,
                'installed'   => $installedVersion,
                'is_outdated' => (version_compare($installedVersion, $latestVersion) === -1) ? true : false,
            ];
        }

        return false;
    }
}
