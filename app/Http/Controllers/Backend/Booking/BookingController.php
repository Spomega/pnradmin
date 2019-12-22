<?php

namespace App\Http\Controllers\Backend\
Booking;
use App\Repositories\Backend\Auth\TransactionRepository;
use Illuminate\Http\Request;
use App\Repositories\Backend\Auth\CompanyRepository;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * @var CompanyRepository
     *
     */
    protected  $companyRepository;

    /**
     * @param TransactionRepository transactionRepository
     *
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;

    }


    public function  index(){

         return view('backend.booking.detail.index');

    }


    // request for pnr details
    public  function show(Request $request){

        $pnr = $request->input("detail");
        $token_response = json_decode($this->getToken()["response"]);
        $guid = $token_response->SecurityGUID;


       //$payload = "{  \n   \"ConfirmationNumber\": \"".strtoupper((string)$pnr)."\"  \n }";
        $payload =  "{  \n   \"actionType\": \"GetReservation\",  \n   \"reservationInfo\": {  \n     \"seriesNumber\": \"299\",  \n     \"confirmationNumber\": \"".strtoupper((string)$pnr)."\"  \n   },  \n   \"securityGUID\": \"".$guid."\",  \n   \"carrierCodes\": [  \n     {  \n       \"accessibleCarrierCode\": \"AW\"  \n     }  \n   ],  \n   \"clientIPAddress\": \"\",  \n   \"historicUserName\": \"\"  \n }";
        //$token_response = json_decode($this->getToken()["response"]);
        //$token = $token_response->SecurityGUID;

        //dd($payload);
        $response = $this->sendRequestToRadix($payload,"ConnectPoint_Reservation/RetrievePNR");
        $httpcode = $response["http_code"];
        $result = $response["response"];

        //dd($result);


        if($httpcode!=500) {


            $res = json_decode($result);

            //$rawResponse = $res->rawResponse;

            $rawResponse = $res;


            $airlinedata = $rawResponse->airlines;



            $logicalFlight = $airlinedata[0]->logicalFlight;
            $returnData = array();


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

            $totalFare = $this->getCharges($rawResponse->history);
            $currentDate = date(DATE_ATOM);
            $returnData["pnr"] = $rawResponse->confirmationNumber;
            $returnData["charges"] = $totalFare;
            $details = (object)$returnData;

             session(['securityGUID'=> $guid,
                 'confirmationNumber'=>$rawResponse->confirmationNumber,
                 'baseAmount'=>(int)$totalFare,
                 'bookingDate'=>$rawResponse->bookDate,
                 'baseCurrency'=>$rawResponse->reservationCurrency,
                 'cardHolder'=>"Naga Radixx",
                 'cardNumber'=>"4111111111111111",
                 'checkNumber'=>"123",
                 'currencyPaid'=> $rawResponse->reservationCurrency,
                 'datePaid' => $currentDate,
                 'expirationDate'=>$currentDate,
                 'exchangeRateDate'=>$currentDate,
                 'paymentComment'=>"Payment for booking with confirmation number ".$rawResponse->confirmationNumber,
                 'paymentAmount'=>(int)$totalFare,
                 'originalCurrency' =>$rawResponse->reservationCurrency,
                 'originalAmount' => (int)$totalFare,
                 'cardCurrency'=>$rawResponse->reservationCurrency,
                 'firstName'=>$rawResponse->reservationContacts[0]->firstName,
                 'lastName'=>$rawResponse->reservationContacts[0]->lastName,
                 'personOrgID'=>$rawResponse->reservationContacts[0]->personOrgID,
                 'city' =>$rawResponse->reservationContacts[0]->city,
                 'address11' => $rawResponse->reservationContacts[0]->address,
                 'country'=>$rawResponse->reservationContacts[0]->country,
                 'countryCode'=>$rawResponse->reservationContacts[0]->country,
                 'email'=>$rawResponse->contactInfos[0]->contactField,
                 'phoneNumber' => $rawResponse->contactInfos[1]->contactField,
                 'age' => $rawResponse->reservationContacts[0]->age,
                 'title' => $rawResponse->reservationContacts[0]->title,
                 'route' => $returnData["route"]

             ]);
            return view('backend.booking.detail.show')->with('details', $details);

        }else{
            echo "Couldn't get a successful response from radix.Please try again";
        }


    }

      //function to get charges
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

    function getToken(){

        $payload ="{}";

        $response = $this->sendRequestToRadix($payload,"security/generate_token");

        return $response;


    }

    function saveReservation(){
        $payload = "{  \n   \"actionType\": \"SaveReservation\",  \n   \"reservationInfo\": {  \n     \"seriesNumber\": \"299\",  \n     \"confirmationNumber\": \"".session("confirmationNumber")."\"  \n   },  \n   \"securityGUID\": \"".session("securityGUID")."\",  \n   \"carrierCodes\": [  \n     {  \n       \"accessibleCarrierCode\": \"AW\"  \n     }  \n   ],  \n   \"clientIPAddress\": \"\",  \n   \"historicUserName\": \"\"  \n }";
        $response =  $this->sendRequestToRadix($payload,"ConnectPoint_Reservation/CreatePNR");

        return $response;
    }

   //pay for booking
    function  payForBooking(Request $request,CompanyRepository $companyRepository){




        $guid = session('securityGUID');

       $payload = "{  \n   \"transactionInfo\": {  \n   
         \"securityGUID\": \"".session('securityGUID')."\",  \n    
          \"carrierCodes\": [  \n   
              {  \n         \"accessibleCarrierCode\": \"AW\"  \n       }  \n     ], 
               \n     \"clientIPAddress\": \"\",  \n   
                \"historicUserName\": \"\"  \n   },  \n   \"reservationInfo\": {  \n   
                  \"seriesNumber\": \"299\", \n   
                    \"confirmationNumber\": \"".session('confirmationNumber')."\"  \n  
                     },  \n   \"externalPayments\": [  \n     {  \n       \"baseAmount\": ".session('baseAmount').",  \n     
                       \"baseCurrency\": \"".session('baseCurrency')."\",  \n    
                          \"cardHolder\": \"Naga Radixx\",  \n     
                            \"cardNumber\": \"4111111111111111\",  \n    
                               \"checkNumber\": 123,  \n    
                                  \"currencyPaid\": \"".session('currencyPaid')."\",  \n     
                                    \"cVCode\": \"123\",  \n     
                                      \"datePaid\": \"".session('datePaid')."\",  \n    
                                         \"documentReceivedBy\": \"naga\",  \n    
                                            \"expirationDate\": \"".session('expirationDate')."\",  \n    
                                               \"exchangeRate\": 0,  \n      
                                                \"exchangeRateDate\": \"".session('expirationDate')."\", 
                                                 \n       \"fFNumber\": \"16\",  \n     
                                                   \"paymentComment\": \"".session('paymentComment')."\",  \n   
                                                       \"paymentAmount\":".session('paymentAmount').",  \n    
                                                          \"paymentMethod\": \"CASH\",  \n     
                                                            \"reference\": \"test\",  \n   
                                                                \"terminalID\": 2,  \n    
                                                                   \"userData\": \"test\",  \n   
                                                                       \"userID\": \"naga\",  \n   
                                                                           \"iataNumber\": \"\",  \n   
                                                                               \"valueCode\": \"123\",  \n   
                                                                                   \"voucherNumber\": -214,  \n    
                                                                                      \"isTACreditCard\": false,  \n    
                                                                                         \"gcxID\": \"1\",  \n   
                                                                                             \"gcxOptOption\": \"1\",  \n 
                                                                                                   \"originalCurrency\": \"".session('originalCurrency')."\",  \n  
                                                                                                        \"originalAmount\": ".session('originalAmount').",  \n    
                                                                                                           \"transactionStatus\": \"APPROVED\",  \n    
                                                                                                              \"authorizationCode\": \"t1\",  \n    
                                                                                                                 \"paymentReference\": \"t2\",  \n  
                                                                                                                      \"responseMessage\": \"t3\",  \n   
                                                                                                                          \"cardCurrency\": \"GHS\",  \n   
                                                                                                                              \"billingCountry\": \"840\",  \n     
                                                                                                                                \"fingerPrintingSessionID\": \"840\",  \n    
                                                                                                                                   \"payor\": {  \n         \"personOrgID\": ".session('personOrgID').",  \n   
                                                                                                                                         \"firstName\": \"".session('firstName')."\",  \n 
                                                                                                                                                 \"lastName\": \"".session('lastName')."\",  \n     
                                                                                                                                                     \"middleName\": \"\",  \n     
                                                                                                                                                         \"age\": ".session('age').",  \n
                                                                                                                                                                \"dOB\": \"1987-10-24T21:01:19.735Z\",  \n
                                                                                                                                                                       \"gender\": \"Male\",  \n
                                                                                                                                                                              \"title\": \"".session('title')."\",  \n
                                                                                                                                                                                    \"nationalityLaguageID\": -2147483648,  \n
                                                                                                                                                                                           \"relationType\": \"Self\",  \n
                                                                                                                                                                                                 \"wBCID\": 1,  \n
                                                                                                                                                                                                     \"pTCID\": 1,  \n
                                                                                                                                                                                                           \"pTC\": \"1\",  \n
                                                                                                                                                                                                                \"travelsWithPersonOrgID\": -2147483648,  \n
                                                                                                                                                                                                                       \"redressNumber\": \"na\",  \n
                                                                                                                                                                                                                           \"knownTravelerNumber\": \"na\",  \n
                                                                                                                                                                                                                                \"marketingOptIn\": true,  \n
                                                                                                                                                                                                                                     \"useInventory\": false,  \n
                                                                                                                                                                                                                                         \"address\": {  \n           \"address11\": \"".session('address11')."\",  \n  
                                                                                                                                                                                                                                                  \"address2\": \"\",  \n    
                                                                                                                                                                                                                                                         \"city\": \"ACCRA\",  \n    
                                                                                                                                                                                                                                                                \"state\": \"\",  \n   
                                                                                                                                                                                                                                                                        \"postal\": \"\",  \n    
                                                                                                                                                                                                                                                                               \"country\": \"".session('country')."\",  \n    
                                                                                                                                                                                                                                                                                      \"countryCode\": \"GH\",  \n   
                                                                                                                                                                                                                                                                                              \"areaCode\": \"\",  \n           \"phoneNumber\": \"".session('phoneNumber')."\",  \n    
                                                                                                                                                                                                                                                                                                     \"display\": \"\"  \n         },  \n   
                                                                                                                                                                                                                                                                                                           \"company\": \"\",  \n         \"comments\": \"\",  \n   
                                                                                                                                                                                                                                                                                                                 \"passport\": \"\",  \n   
                                                                                                                                                                                                                                                                                                                       \"nationality\": \"\",  \n     
                                                                                                                                                                                                                                                                                                                           \"profileId\": -2147483648,  \n     
                                                                                                                                                                                                                                                                                                                               \"isPrimaryPassenger\": true,  \n     
                                                                                                                                                                                                                                                                                                                                   \"contactInfos\": [  \n           {  \n             \"contactID\": 1,  \n     
                                                                                                                                                                                                                                                                                                                                           \"personOrgID\": -2141,  \n       
                                                                                                                                                                                                                                                                                                                                                 \"contactField\": \"3214446666\",  \n        
                                                                                                                                                                                                                                                                                                                                                      \"contactType\": \"HomePhone\",  \n      
                                                                                                                                                                                                                                                                                                                                                             \"extension\": \"\",  \n        
                                                                                                                                                                                                                                                                                                                                                                  \"countryCode\": \"\",  \n     
                                                                                                                                                                                                                                                                                                                                                                          \"areaCode\": \"\",  \n             \"phoneNumber\": \"".session('phoneNumber')."\",  \n       
                                                                                                                                                                                                                                                                                                                                                                                \"display\": \"\",  \n             \"preferredContactMethod\": false  \n           }  \n 
                                                                                                                                                                                                                                                                                                                                                                                        ],  \n         \"frequentFlyerNumber\": \"na\",  \n         \"suffix\": \"\"  \n    
                                                                                                                                                                                                                                                                                                                                                                                           },  \n       \"result\": \"\",  \n       \"transactionID\": \"\",  \n     
  
  
                                                                                                                                                                                                                                                                                                                                                                                         \"responseCode\": \"\",  \n       \"ancillaryData01\": \"\",  \n       \"ancillaryData02\": \"\",  \n       \"ancillaryData03\": \"\",  \n       \"ancillaryData04\": \"\",  \n       \"ancillaryData05\": \"\",  \n       \"processorID\": \"\",  \n       \"merchantID\": \"na\",  \n       \"processorName\": \"\",  \n       \"metaData\": [  \n         {  \n           \"keyName\": \"\",  \n           \"value\": \"\"  \n         }  \n       ]  \n     }  \n   ]  \n }";

     //dd($payload);

        $response = $this->sendRequestToRadix1($payload,"ConnectPoint_Fulfillment/InsertExternalProcessedPayment");
        $this->saveReservation();
       //dd($response);

        $result  = json_decode($response["response"]);

        $payment  = $result->payments;

        $this->transactionRepository->create([
            'confirmation_number'=>$result->confirmationNumber,
            'date_paid' => date("Y-m-d",strtotime($payment[0]->datePaid)),
            'total_cost' => (double)$payment[0]->baseAmount,
            'route'=> session('route'),
            'comment' => session('paymentComment'),
            'passenger_name' =>  session('firstName')." ".session('lastName'),
            'phone_number' => session('phoneNumber'),
            'user_id'=>  $request->user()->id,
            'base_currency' => $payment[0]->currencyPaid,
            'company_id' =>  $request->user()->company

        ]);

        return view('backend.booking.detail.transaction')
            ->withTransactions($this->transactionRepository
                ->orderBy('id','asc')
                ->paginate());
    }


    //send request to Radix
    function  sendRequestToRadix($payload,$endpoint){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, session('url').$endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
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

        $response = array();

        $response["response"] = $result;
        $response["http_code"] = $httpcode;

        return $response;

    }

    function  sendRequestToRadix1($payload,$endpoint){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, session('url').$endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Basic ZWR3YXJkOnBpZQ==';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

       // dd($result);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }


        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = array();

        $response["response"] = $result;
        $response["http_code"] = $httpcode;

        return $response;

    }
}
