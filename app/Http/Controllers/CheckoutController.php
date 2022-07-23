<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Fee;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Product;
use App\Models\Region;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

class CheckoutController extends Controller
{

    protected StripeClient $stripeClient;
    protected DatabaseManager $databaseManager;

    public function __construct(StripeClient $stripeClient, DatabaseManager $databaseManager)
    {
        $this->stripeClient = $stripeClient;
        $this->databaseManager = $databaseManager;
    }

/*
    public function Boxtal(): void
    {
        try {
            $client = new Client();
            $res = $client->request('POST', 'https://envoimoinscher.com/api/v1/order', [
                'auth' => ['admin@classic-parts-finder.fr', 'C-P-F2022Boxtal'],
                'headers' => [
                    'Accept' => 'application/xml',
                    'Content-Type' => 'application/xml',
                    'access_key' => '5buasl1y',
                ],
                'query' => [
                    'expediteur.pays' => 'FR',
                    'expediteur.code_postal' => '44000',
                    'expediteur.type' => 'particulier',
                    'expediteur.ville' => 'nantes',
                    'expediteur.adresse' => 'rue-Racine',
                    'expediteur.civilite' => 'M',
                    'expediteur.prenom' => 'Jon',
                    'expediteur.nom' => 'Snow',
                    'expediteur.email' => 'jsnow@test.com',
                    'expediteur.tel' => '0561070000',
                    'expediteur.societe' => 'Boxtal',
                    'destinataire.pays' => 'FR',
                    'destinataire.etat' => 'ND',
                    'destinataire.type' => 'particulier',
                    'destinataire.code_postal' => '75002',
                    'destinataire.ville' => 'Paris',
                    'destinataire.adresse' => 'Grand-Lebrun',
                    'destinataire.civilite' => 'M',
                    'destinataire.prenom' => 'Jon',
                    'destinataire.nom' => 'Snow',
                    'destinataire.email' => 'jsnow@test.com',
                    'destinataire.tel' => '0561070000',
                    'destinataire.societe' => 'Boxtal',
                    'colis_1.poids' => 1,
                    'colis_1.longueur' => 1,
                    'colis_1.largeur' => 1,
                    'colis_1.hauteur' => 1,
                    'colis.valeur' => '30.0',
                    'collecte' => '2022-04-19',
                    'delai' => 'aucun',
                    'code_contenu' => '30100',
                    'operateur' => 'DHLE',
                ],

            ])->getBody()->getContents();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }


        $encode_response = json_encode(simplexml_load_string($res));

        $decode_response = json_decode($encode_response, TRUE);
        //dd($decode_response['shipment']);
        dd($encode_response);

        die();

    }

*/


    public function index($id)
    {

        $product = Product::query()->where('id', $id)->first();

        $countries = Country::query()->where('active', true)->get();

        $fees = Fee::all();

        $default_fee = Fee::query()->where('fee_type', 'all')->first();

        $commission = $default_fee['percent'] / 100;
        $client_pay = 0;
        $platform_earning = $product['price'] * $commission;
        $seller_earning = null;
        $price_pay = null;

        if ($default_fee['user_type'] == 'acheteur') {
            $seller_earning = $product['price'];
            $price_pay = $product['price'] + $platform_earning;
        }
        if ($default_fee['user_type'] == 'vendeur') {
            $seller_earning = $product['price'] - $platform_earning;
            $price_pay = $product['price'];
        }

        foreach ($fees as $fee) {
            if ($fee['fee_type'] == 'products' || $fee['fee_type'] == 'merchants') {

                if (in_array($product['price'], range($fee['min_price'], $fee['max_price']))) {
                    if ($fee['fee_type'] == 'products') {
                        if ($product['id'] == $fee['ids']) {
                            $commission = $fee['percent'] / 100;
                            $platform_earning = $product['price'] * $commission;
                            if ($fee['user_type'] == 'acheteur') {
                                $seller_earning = $product['price'];
                                $price_pay = $product['price'] + $platform_earning;
                            }
                            if ($fee['user_type'] == 'vendeur') {
                                $seller_earning = $product['price'] - $platform_earning;
                                $price_pay = $product['price'];
                            }
                        }
                    } elseif ($fee['fee_type'] == 'merchants') {
                        if ($product->merchant_id == $fee['ids']) {
                            $commission = $fee['percent'] / 100;
                            $platform_earning = $product['price'] * $commission;
                            if ($fee['user_type'] == 'acheteur') {
                                $seller_earning = $product['price'];
                                $price_pay = $product['price'] + $platform_earning;
                            }
                            if ($fee['user_type'] == 'vendeur') {
                                $seller_earning = $product['price'] - $platform_earning;
                                $price_pay = $product['price'];
                            }
                        }
                    }
                }
            }
        }

        // Remove old session
        session()->forget('prices');

        $data['product_id'] = $product['id'];
        $data['product_name'] = $product->name;
        $data['client_pay'] = ceil($client_pay);
        $data['product_price'] = ceil($product['price']);
        $data['merchant_id'] = $product->merchant_id;
        $data['commission'] = $commission * 100;
        $data['platform_earning'] = ceil($platform_earning);
        $data['seller_earning'] = ceil($seller_earning);
        $data['price_pay'] = ceil($price_pay);

        session(['prices' => $data]);
        session(['product' => $product]);

        return view('checkout', compact('product', 'countries'));
    }

    public function checkoutProduct(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'region' => 'required',
            'city' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'zip' => 'required',
            'shipping' => 'required'
        ]);

        // Remove old session
        session()->forget('checkout');

        $country = Country::query()->find($request->country_id);

        // Create new session
        session(['checkout' => $validated]);

        $product = session('product');

        //dd($product->merchant->toArray(), $request);

        //dd($product->toArray());

        $query_data = [
            'expediteur.pays'           => Str::upper($product->merchant->country->code),
            'expediteur.code_postal'    => $product->merchant->merchant_zip,
            'expediteur.type'           => 'particulier',
            'expediteur.ville'          => $product->merchant->merchant_city,
            'expediteur.adresse'        => $product->merchant->merchant_address,
            'expediteur.prenom'         => $product->merchant->user[0]->firstname,
            'expediteur.nom'            => $product->merchant->user[0]->lastname,
            'expediteur.email'          => $product->merchant->user[0]->email,
            'expediteur.tel'            => $product->merchant->user[0]->phone,

            'destinataire.pays'         => Str::upper($country->code),
            'destinataire.type'         => 'particulier',
            'destinataire.code_postal'  => $request->zip,
            'destinataire.ville'        => $request->city,
            'destinataire.adresse'      => $request->address,
            'destinataire.prenom'       => $request->firstname,
            'destinataire.nom'          => $request->lastname,
            'destinataire.email'        => $request->email,
            'destinataire.tel'          => $request->phone,

            'colis_1.poids'             => $product->weight,
            'colis_1.longueur'          => $product->depth,
            'colis_1.largeur'           => $product->width,
            'colis_1.hauteur'           => $product->height,
            'colis.description'         => $product['name'],
            'operateur'                 => $request->shipping,
            'code_contenu'              => rand(10000, 99999),
        ];
        //


        try {
            $client = new Client();
            $res = $client->request('GET', 'https://envoimoinscher.com/api/v1/cotation',
                [
                    'auth' => ['admin@classic-parts-finder.fr', 'C-P-F2022Boxtal'],
                    'headers' =>
                        [
                            'Accept' => 'application/xml',
                            'Content-Type' => 'application/xml',
                            'access_key' => '5buasl1y',
                        ],
                    'query' => $query_data,
                ])->getBody()->getContents();

        } catch (GuzzleException $exception) {
            dd($exception->getMessage());
        }


        $encode_response = json_encode(simplexml_load_string($res));

        $decode_response = json_decode($encode_response, TRUE);
        //dd($decode_response);
        //dd($decode_response['shipment']);



        if (isset($decode_response['shipment']['offer'][0]))
        {
            $query_data['service'] = $decode_response['shipment']['offer'][0]['service']['code'];
        }
        else
        {
            $query_data['service'] = $decode_response['shipment']['offer']['service']['code'];
        }

        session(['request_data' => $query_data]);

        if (isset($decode_response['shipment']['offer'])) {
            session(['total_prices' => $decode_response]);
        } else {
            return back()->withErrors('There is a problem with the shipping information');
        }

        return redirect()->route('checkout.shipping', ['locale' => app()->getLocale()]);
    }

    public function shippingPrice()
    {
        $total_prices = session('total_prices');
        $prices = session('prices');
        $checkout = session('checkout');

        //session(['prices.price_pay' => $total_prices['shipment']['offer']['price']['tax-inclusive'] + $prices['price_pay']]);

        //$prices = session('prices');
        //dd($total_prices);

        return view('shipping_price', compact('total_prices', 'prices'));
    }

    public function paymentForm(Request $request)
    {

        $data = session('prices');
        $product_id = $data['product_id'];
        //$product_price = $data['product_price'];
        $product_price = $request['product_amount'];

        $seller = Merchant::find($data['merchant_id']);
        if (!$seller->completed_stripe_onboarding) {
            return back()->withErrors(['message' => 'The seller has not set up the payment method.']);
        }

        return view('payement', compact('product_price', 'product_id', 'data'));
    }


    public function charge(Request $request)
    {
        $data = session('prices');


        $this->validate($request, [
            'stripeToken' => ['required', 'string']
        ]);

        $seller = Merchant::find($data['merchant_id']);

        try {

            // Purchase a product
            $charge = $this->stripeClient->charges->create([
                'amount' => $request['product_amount'] * 100,
                'currency' => 'EUR',
                'source' => $request->stripeToken,
                'description' => 'New Order : ' . $data['product_name']
            ]);

            // Transfer funds to seller
            $this->stripeClient->transfers->create([
                'amount' => $data['seller_earning'] * 100,
                'currency' => 'EUR',
                'source_transaction' => $charge->id,
                'destination' => $seller->stripe_connect_id,
                'description' => 'Transfer To : ' . $seller->merchant_name
            ]);

        } catch (ApiErrorException $exception) {
            dd($exception->getMessage());
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        if ($charge['id']) {

            $order_data = session('checkout');
            $price = session('prices');

            $order = new Order();
            $order['merchant_id'] = $price['merchant_id'];
            $order['product_id'] = $price['product_id'];
            $order['firstname'] = $order_data['firstname'];
            $order['lastname'] = $order_data['lastname'];
            $order['address'] = $order_data['address'];
            $order['country_id'] = $order_data['country_id'];
            $order['region'] = $order_data['region'];
            $order['city'] = $order_data['city'];
            $order['email'] = $order_data['email'];
            $order['phone'] = $order_data['phone'];
            $order['zip'] = $order_data['zip'];
            $order['price'] = $price['product_price'];
            $order['platform_commission'] = $price['commission'];
            $order['your_earnings'] = $price['seller_earning'];
            $order['platform_earnings'] = $price['platform_earning'];
            $order->save();

            return redirect()->route('result', ['locale' => app()->getLocale()])->with('success', 'Payment completed successfully');

        } else {
            return redirect()->route('result', ['locale' => app()->getLocale()])->with('message', 'Something went wrong');
        }
    }

    public function result()
    {
        $request_data= session('request_data');


        //dd($request_data);
        try {
            $client = new Client();
            $res = $client->request('POST', 'https://envoimoinscher.com/api/v1/order', [
                'auth' => ['admin@classic-parts-finder.fr', 'C-P-F2022Boxtal'],
                'headers' => [
                    'Accept' => 'application/xml',
                    'Content-Type' => 'application/xml',
                    'access_key' => '5buasl1y',
                ],
                'query' => $request_data,

            ])->getBody()->getContents();
        } catch (\Exception $exception)
        {
            dd($exception);
        }

        $encode_response = json_encode(simplexml_load_string($res));

        $decode_response = json_decode($encode_response, TRUE);

        dd($decode_response);

        return view('result');
    }

    public function orders()
    {
        return Order::all();
    }

    public function getRegions(Request $request)
    {
        $parent_id = $request['cat_id'];

        $regions = Region::query()->where('country_id', $parent_id)->get();

        return response()->json([
            'regions' => $regions,
        ]);
    }

}
