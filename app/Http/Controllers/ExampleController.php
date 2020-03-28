<?php

namespace App\Http\Controllers;

use http\Env\Request;

ini_set('memory_limit', '1GB');

class ExampleController extends Controller
{

    private $apiUrl = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/";
    private $headerVars = array(
        "x-rapidapi-host: coronavirus-monitor.p.rapidapi.com",
        "x-rapidapi-key: 9a182b9ddamshef0fdd8891477ebp175fb9jsna8d94bc01b0c"
    );

    private $validsEndpoints = array('affected', 'cases_by_country', 'usastates');

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        header('Content-Type: application/json');
    }

    /**
     * @param $type
     *
     * @return false|string
     */
    public function getData($type = 'affected')
    {

        if (in_array($type, $this->validsEndpoints)) {

            $result = $this->connectApi($type);

            if (!empty($result['error'])) {
                echo json_encode($result);
            }

            echo $result;

            return;
        }

        return response(array('error' => true, 'message' => 'not found'), 404);

    }


    /**
     * @param $type
     *
     * @return array
     */
    public function connectApi($type)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . $type . '.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $this->headerVars,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
            return array('error' => true, 'message' => "cURL Error #:" . $err);
        }

        return $response;

    }
}
