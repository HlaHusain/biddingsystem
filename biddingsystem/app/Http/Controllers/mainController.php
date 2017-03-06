<?php

namespace App\Http\Controllers;

use App\Bid;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class mainController extends Controller
{
    //show all bids
    public function index()
    {

        $products = Product::all();
        $bids = Bid::all();
        $arr = Array('product' => $products, 'bid' => $bids);


        return view('products.index', $arr);

    }
// show bids => $id
    public function show($id)
    {


        $product = Product::find($id);
        return response(array(
            'error' => false,
            //'products' =>$product->toArray(),
        ), 200);

    }

//admin create bids
    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $newproduct = new Product();

            $newproduct->name = $request->input('name');
            $newproduct->desc = $request->input('desc');
            $newproduct->save();


            $newbid = new Bid();
            $newbid->product_id = $newproduct->id;
            $newbid->price = $request->input('price');
            $newbid->incUnit = $request->input('incUnit');
            $newbid->period = $request->input('period');
            $newbid->startTime = $request->input('startTime');
            $newbid->isdeleted = 0;

            $newbid->save();
        }

        return view('products.add');


    }
//admin update bid
    public function update(Request $request, $id)
    {

        if ($request->isMethod('post')) {
            $product = Product::find($id);
            $bid = Bid::where('product_id', $id)->first();

            $product->name = $request->input('name');
            $product->desc = $request->input('desc');

            $bid->price = $request->input('price');
            $bid->incUnit = $request->input('incUnit');
            $bid->period = $request->input('period');
            $product->save();
            $bid->save();

            return redirect("api/v1/products");
        } else {
            try{
                $statusCode = 200;
            $product = Product::find($id);
            $bid = Bid::where('product_id', $id)->first();



                $response['product'][] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'desc' => $product->desc,
                    'isdeleted' => $product->isdeleted,
                ];
                $response['bid'][] = [
                    'id' => $bid->id,
                    'price' => $bid->price,
                    'incUnit' => $bid->incUnit,
                    'period' => $bid->period,
                    'startTime' => $bid->startTime,
                ];

        }catch (Exception $e){
        $statusCode = 400;
    }finally{
        return Response::json($response, $statusCode);
    }



//            $arr = Array('product' => $product, 'bid' => $bid);
//            return view('products.edit', $arr);
        }


    }

    public function delete($id)
    {
        $product = Product::find($id);

        $bid = Bid::where('product_id', $product->id)->first();
        echo $bid;
        $product->delete();
        $bid->delete();
    }

//bid product
    public function bidProduct(Request $request, $product_id, $bid_id)
    {

        if ($request->isMethod('post')) {
            DB::transaction(function ()use($request,$product_id,$bid_id){

                $userName=Session::get('name');
                $userId=User::where('name' ,$userName )->lockForUpdate()->value('id');

                $newTransaction = new Transaction();
                $newTransaction->bidValue = $request->input('bidValue');
                $newTransaction->product_id = $product_id;
                $newTransaction->bid_id = $bid_id;
                // $userId=Session::get('id');
                $newTransaction->user_id = $userId;
                $newTransaction->save();
                DB::commit();
            });


        }
        return redirect("api/v1/products/Uindex");
    }
//return current bids
    public function Uindex()
    {
        try{
            $statusCode = 200;
        $bids = Bid::where('isdeleted', 0)->get();

            foreach($bids as $bid){
                $productName=Product::where('id' , $bid->product_id)->value('name');
                $productDesc=Product::where('id' , $bid->product_id)->value('desc');
                $Particpant=Transaction::where('product_id',$bid->product_id)->get();
                $maxCurrentBid=Transaction::where('product_id',$bid->product_id)->max('bidValue');
                $numofParticpant = count($Particpant);


                $response['bid'][] = [
                    'id' => $bid->id,
                    'price' => $bid->price,
                    'incUnit' => $bid->incUnit,
                    'period' => $bid->period,
                    'startTime' => $bid->startTime,
                    'winner_id' => $bid->winner_id,
                    'productName' => $productName,
                    'productDesc' =>$productDesc,
                    'numofParticpant' => $numofParticpant,
                    'maxCurrentBid' => $maxCurrentBid

                ];
            }
//            $arr = Array('bids' => $bids);
//            return view('products.Uview', $arr);


    }catch (Exception $e){
$statusCode = 400;
}finally{
    return Response::json($response, $statusCode);
}


//

    }



    //return user closed bids
public function myclosedbids()
{
    {
        try {
            $statusCode = 200;
            //$userId=Session::get('id');
            $userName = Session::get('name');
            $userId = User::where('name', $userName)->value('id');
            $transactions = Transaction::where('user_id', $userId )->where('isdeleted', 1)->get();



            foreach ($transactions as $transaction) {
                $productName=Product::where('id' , $transaction->product_id)->value('name');
                $productDesc=Product::where('id' , $transaction->product_id)->value('desc');
                $Particpant=Transaction::where('product_id',$transaction->product_id)->get();
                $winnerid=Bid::where('product_id',$transaction->product_id)->where('isdeleted', 1)->value('winner_id');
                $maxCurrentBid=Transaction::where('product_id',$transaction->product_id)->max('bidValue');
                $winnerName = User::where('id',$winnerid )->value('name');
                $numofParticpant = count($Particpant);

                $response['myclosedbids'][] = [
                    'id' => $transaction->id,
                    'bidValue' => $transaction->bidValue,
                    'isdeleted' => $transaction->isdeleted,
                    'product_id' => $transaction->product_id,
                    'bid_id' => $transaction->bid_id,
                    'user_id' => $transaction->user_id,
                    'productName' => $productName,
                    'productDesc' => $productDesc,
                    'maxBid' => $maxCurrentBid,
                    'winnerName' => $winnerName

                ];
            }
        } catch (Exception $e) {
            $statusCode = 400;
        } finally {
            return Response::json($response, $statusCode);
        }


//        $arr = Array('transactions' => $transactions);
//        return view('products.mybids', $arr);
    }
}

//return user open bids
    public function mybids()
    {
try {
    //$userId=Session::get('id');
    $userName = Session::get('name');
    $userId = User::where('name', $userName)->value('id');
    $transactions = Transaction::where('user_id', $userId )->where('isdeleted', 0)->get();

    $statusCode = 200;

    foreach ($transactions as $transaction) {
        $productName=Product::where('id' , $transaction->product_id)->value('name');
        $productDesc=Product::where('id' , $transaction->product_id)->value('desc');
        $Particpant=Transaction::where('product_id',$transaction->product_id)->get();
        $maxCurrentBid=Transaction::where('product_id',$transaction->product_id)->max('bidValue');
        $numofParticpant = count($Particpant);
        $response['myopenbids'][] = [
            'id' => $transaction->id,
            'bidValue' => $transaction->bidValue,
            'isdeleted' => $transaction->isdeleted,
            'product_id' => $transaction->product_id,
            'bid_id' => $transaction->bid_id,
            'user_id' => $transaction->user_id,
            'productName' => $productName,
            'productDesc' => $productDesc,
            'maxBid' => $maxCurrentBid


        ];
    }
}
catch (Exception $e){
    $statusCode = 400;
}finally{
    return Response::json($response, $statusCode);
}


//        $arr = Array('transactions' => $transactions);
//        return view('products.mybids', $arr);

    }




//check if bids time is over and closed bids
    public function checkTime()
    {

        $bids = Bid::where("isdeleted", 0)->get();
        //echo $bids . "<br> <br>";
        foreach ($bids as $bid) {
            $startTime = $bid->startTime;
            $period = $bid->period;
            $startTimeSec = strtotime($startTime);
            $periodSec = $period * 60 * 60;
            $endSec = $startTimeSec + $periodSec;
            $end = gmdate("y-m-d H:i:s", $endSec);
            echo "end Time" . $end . "</br>";
            $saveTime = strtotime($end);
            $thisTime = time(); // Current time
            echo "this Time:" . gmdate("y-m-d H:i:s", $thisTime) . "<br>";
            $diffTime = ($saveTime - $thisTime); // Difference in time

            if ($diffTime >= 1) {
                echo 'Time remaining until next run is in ' . gmdate("H:i:s", $diffTime) . "<br>";

            } else {
                echo 'Timer expired.' . "<br>";
                echo 'bid_id' . $bid->id;

                $bid->isdeleted = 1;
                $bid->winner_id =$this->claculateWinnerid($bid->id);
                echo $bid->winner_id;
                $bid->save();
                $transactions=Transaction::where('bid_id',$bid->id)->get();
                foreach ($transactions as $transaction){
                    $transaction->isdeleted=1;
                    $transaction->save();
                }


            }



        }



    }

//return winnerID
public function claculateWinnerid($id){

    $Particpant = Transaction::where("bid_id", $id)->get();
    $maxBid = Transaction::where('bid_id', $id)->max('bidValue');
    $winnerId = Transaction::where('bidValue', $maxBid)->value('user_id');

    echo "bid id" . $id . "<br>";
    echo 'Particpant' . count($Particpant) . "<br>";
    echo '$maxBid' . $maxBid . "<br>";
    echo '$Bid' . $winnerId . "<br>";
    return $winnerId;

}

//return closed bids for admin
public function closedbids()
{


    {
        try {
            $statusCode = 200;
            //$userId=Session::get('id');
            $bids = Bid::where('isdeleted', 1)->get();

            foreach($bids as $bid) {
                $winnerid=$bid->winner_id;
                $bestBid = Transaction::where('user_id', $winnerid)->where('product_id',$bid->product_id)->value('bidValue');
                $winnerName = User::where('id',$winnerid )->value('name');
                $productName=Product::where('id' , $bid->product_id)->value('name');
                $productDesc=Product::where('id' , $bid->product_id)->value('desc');

                $response['closedbids'][] = [
                    'id' => $bid->id,
                    'price' => $bid->price,
                    'incUnit' => $bid->incUnit,
                    'period' => $bid->period,
                    'startTime' => $bid->startTime,
                    'winner_id' => $bid->winner_id,
                    'isdeleted' => $bid->isdeleted,
                    'bestBid' => $bestBid,
                    'productName' => $productName,
                    'productDesc' => $productDesc,
                    'winnerName' => $winnerName


                ];


            }

        } catch (Exception $e) {
            $statusCode = 400;
        } finally {
            return Response::json($response, $statusCode);
        }


//        $arr = Array('transactions' => $transactions);
//        return view('products.mybids', $arr);
    }

}

//return running bids for admin
public function runningbids(){

        {
            try {
                $statusCode = 200;
                //$userId=Session::get('id');
                $bids = Bid::where('isdeleted', 0)->get();
                //$transactions=Transaction::all();



                foreach($bids as $bid){
                    $ParticpantId=Transaction::where('product_id',$bid->product_id)->value('user_id');
                    $bidValue=Transaction::where('product_id',$bid->product_id)->value('bidValue');
                    $username = User::where('id',$ParticpantId )->value('name');
                    $productName=Product::where('id' , $bid->product_id)->value('name');
                    $productDesc=Product::where('id' , $bid->product_id)->value('desc');
                    $Particpant=Transaction::where('product_id',$bid->product_id)->get();
                    $maxCurrentBid=Transaction::where('product_id',$bid->product_id)->max('bidValue');
                    $numofParticpant = count($Particpant);

                    $response['runningbids'][] = [
                        'id' => $bid->id,
                        'price' => $bid->price,
                        'incUnit' => $bid->incUnit,
                        'period' => $bid->period,
                        'startTime' => $bid->startTime,
                        'winner_id' => $bid->winner_id,
                        'isdeleted' => $bid->isdeleted,
                        'ParticpantName' => $username ,
                        'BidValue' => $bidValue,
                        'productName' => $productName,
                        'productDesc' => $productDesc,
                        'Particpant' => $Particpant,
                        'maxCurrentBid' => $maxCurrentBid ,
                        '$numofParticpant' => $numofParticpant

                    ];



                }
            } catch (Exception $e) {
                $statusCode = 400;
            } finally {
                return Response::json($response, $statusCode);
            }


//        $arr = Array('transactions' => $transactions);
//        return view('products.mybids', $arr);
        }

    }




}




