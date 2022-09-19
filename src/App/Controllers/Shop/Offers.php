<?php
namespace Cosmic\App\Controllers\Shop;

use Cosmic\App\Config;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Shop;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

use Cosmic\App\Library\HotelApi;
use Cosmic\App\Library\Paypal as ClientPaypal;

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

use stdClass;

class Offers
{
    public function createorder()
    {     
        
        ValidationService::validate([
            'orderId'  => 'required'
        ],[
            'orderId' => input('orderId')
        ]);
      
        $rcon = HotelApi::execute('executecommand', ['user_id' => request()->player->id, 'about']);
        if(!$rcon->status ?? null) {
            response()->json(["status" => "error", "message" => "Item can only be purchased when our hotel is back online :-)!"]);
        }  
      
        $offer = Shop::getOfferById(input('orderId'));
      
        if(!$offer) {
            response()->json(["status" => "error", "result" => "offline", "message" => "Order type not found"]);
        }
      
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = self::buildRequestBody($offer);

        $client = ClientPaypal::client();
        $response = $client->execute($request);
      
        if($response) {
            $created = Shop::insertOffer(request()->player->id, input('orderId'), $response->result->id, $response->result->purchase_units[0]->payee->merchant_id, $response->result->status);
            if($created) {
                return json_encode($response->result, JSON_PRETTY_PRINT);
            }
        }
    }
  
    public static function captureorder()
    {     
        ValidationService::validate([
            'orderId' => 'required',
            'offerId' => 'required'
        ],[
            'orderId' => input('orderId'), 
            'offerId' => input('offerId')
        ]);
      
        $offer = Shop::getOfferById(input('offerId'));
      
        if(!$offer) {
            response()->json(["status" => "error", "message" => "Order type not found"]);
        }
      
        $request = new OrdersCaptureRequest(input('orderId'));
        $client = ClientPaypal::client();
        $response = $client->execute($request);
        
        if($response) {
            $created = Shop::updateOffer($response->result->id, $response->result->status);
            if($created) {
                return json_encode($response->result, JSON_PRETTY_PRINT);
            }
        }
    }

    private static function buildRequestBody($offer)
    {
        return array(
            'intent' => 'CAPTURE',
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'amount' =>
                                array(
                                    'currency_code' => Core::settings()->paypal_currency,
                                    'value' => $offer->price
                                )
                        )
                )
        );
    }
  
    public function status()
    {
        ValidationService::validate([
            'orderId'  => 'required',
            'status'   => 'required|pattern:^(?:CANCELDORFAILED)$',
        ],[
            'orderId' => input('orderId'), 
            'status' => input('status')
        ]);
      
        Shop::update(input('orderId'), input('status'), 'status');
        response()->json(["status" => "success", "error" => "Order changed to: " . input('status')]);
    }
  
    public function validate()
    {
        ValidationService::validate([
            'orderId'  => 'required'
        ],[
            'orderId' => input('orderId')
        ]);
        $order = Shop::getPayment(input('orderId'));
        if ($order == null) {
            response()->json(["status" => "error", "message" => LocaleService::get('shop/offers/invalid_transaction')]);
        }

        if ($order->status != 'COMPLETED') {
            response()->json(["status" => "error", "loadpage" => '/shop/order/view/' . input('orderId'), "message" => "Order isn't ready yet, please check if your payment has been accepted"]);
        } else if ($order->status == 'COMPLETED' && $order->delivered == "NO") {
          
            $offer = Shop::getOfferById($order->offer_id);
            $data = json_decode($offer->data, true);

            foreach($data as $key => $value) {
                HotelApi::execute($key, ['user_id' => request()->player->id, $value], true);
            }
          
            Shop::update(input('orderId'), "YES", 'delivered');
            response()->json(["status" => "success", "message" => "Order delivered has been delivered"]);
        }
    }
}
