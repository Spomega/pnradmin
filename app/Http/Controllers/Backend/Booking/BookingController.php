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

        //

        $pnr = $request->input("detail");

        $data = array("ConfirmationNumber" => (string)$pnr);

        $client = new Client();



        try{
            $response = $client->request('POST', 'http://ec2-54-234-113-88.compute-1.amazonaws.com/awa/booking/get_booking',
                ["headers" => ['Content-Type' => 'application/json',
                    'Authorization' => 'Basic ZWR3YXJkOnBpZQ=='],
                    'json'=>$data ]);


            $res = json_decode($response->getBody()->getContents());

           //dd($response->getStatusCode());

            $rawResponse = $res->rawResponse;


            $airlinedata = $rawResponse->airlines;
            $logicalFlight = $airlinedata[0]->logicalFlight;
            $returnData = array();

            if(sizeof($logicalFlight)>1){
                $returnData["type"] = "Return Trip";
                $airlinePersonReturn = $logicalFlight[1]->physicalFlights[0]->customers[0]->airlinePersons[0];
                $returnData["route"] = $logicalFlight[1]->originName . " - ". $logicalFlight[1]->destinationName;
                $returnData["name"] = $airlinePersonReturn->firstName ." ". $airlinePersonReturn->lastName;
                $returnData["departure"] = $logicalFlight[1]->departureTime;
                $returnData["arrival"] = $logicalFlight[1]->arrivalTime;

            }
            else
            {
                $returnData["type"] = "One Way";
                $airlinePersonOneWay = $logicalFlight[0]->physicalFlights[0]->customers[0]->airlinePersons[0];
                $returnData["route"] = $logicalFlight[0]->originName . " - ". $logicalFlight[0]->destinationName;
                $returnData["name"] = $airlinePersonOneWay->firstName ." ". $airlinePersonOneWay->lastName;
                $returnData["departure"] = $logicalFlight[1]->departureTime;
                $returnData["arrival"] = $logicalFlight[1]->arrivalTime;

            }



            $returnData["pnr"] = $rawResponse->confirmationNumber;
            $returnData["charges"]   =  $this->getCharges($rawResponse->history);
            $details = (object)$returnData;

           // dd($details);

            return view('backend.booking.detail.show')->with('details',$details);

        }
        catch (\Exception $e){
            $e->getMessage();
            echo $e->getCode();
            echo $e->getMessage();
            echo "------------------------------------------------------";
            echo $e->getTraceAsString();
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
