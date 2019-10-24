<?php

namespace App\Http\Controllers\Backend\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class BookingController extends Controller
{
    //

    public function  index(){

         return view('backend.booking.detail.index');

    }



    public  function show(Request $request){



        $pnr = $request->input("detail");

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://ec2-54-234-113-88.compute-1.amazonaws.com/awa/booking/get_booking');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{  \n   \"ConfirmationNumber\": \"".(string)$pnr."\"  \n }");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Basic ZWR3YXJkOnBpZQ==';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }


        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpcode!=500) {


            /*
             $client = new Client();
            // try{
             $response = $client->request('POST', 'http://ec2-54-234-113-88.compute-1.amazonaws.com/awa/booking/get_booking',
                 ["headers" => ['Content-Type' => 'application/json',
                     'Authorization' => 'Basic ZWR3YXJkOnBpZQ=='],
                     'json'=>$data ]);
              * */
            $res = json_decode($result);

            //dd($response->getStatusCode());

            $rawResponse = $res->rawResponse;


            $airlinedata = $rawResponse->airlines;


            $logicalFlight = $airlinedata[0]->logicalFlight;
            $returnData = array();
          //  dd($logicalFlight);

            if (sizeof($logicalFlight) > 1) {
                $returnData["type"] = "Return Trip";
                $airlinePersonReturn = $logicalFlight[1]->physicalFlights[0]->customers[0]->airlinePersons[0];
                $returnData["route"] = $logicalFlight[1]->originName . " - " . $logicalFlight[1]->destinationName;
                $returnData["name"] = $airlinePersonReturn->firstName . " " . $airlinePersonReturn->lastName;
                $returnData["departure"] = $logicalFlight[1]->departureTime;
                $returnData["arrival"] = $logicalFlight[1]->arrivaltime;

            } else {
                $returnData["type"] = "One Way";
                $airlinePersonOneWay = $logicalFlight[0]->physicalFlights[0]->customers[0]->airlinePersons[0];
                $returnData["route"] = $logicalFlight[0]->originName . " - " . $logicalFlight[0]->destinationName;
                $returnData["name"] = $airlinePersonOneWay->firstName . " " . $airlinePersonOneWay->lastName;
                $returnData["departure"] = $logicalFlight[0]->departureTime;
                $returnData["arrival"] = $logicalFlight[0]->arrivaltime;

            }


            $returnData["pnr"] = $rawResponse->confirmationNumber;
            $returnData["charges"] = $this->getCharges($rawResponse->history);
            $details = (object)$returnData;
            //dd($details);

            return view('backend.booking.detail.show')->with('details', $details);

            // }
            //catch (\Exception $e){
            //    $e->getMessage();
            //  echo $e->getCode();
            // echo $e->getMessage();
            //  echo "------------------------------------------------------";
            // echo $e->getTraceAsString();
            // }
        }else{
            echo "Couldn't get a successful response from radix.Please try again";
        }


    }



    function getCharges($history)
    {
        foreach ($history as $hist)
        {
            if(stripos($hist->details,"Total Charges:") !== false) {

                return substr($hist->details,stripos($hist->details,":")+1,12);
            }
        }

        return "none";
    }

}
