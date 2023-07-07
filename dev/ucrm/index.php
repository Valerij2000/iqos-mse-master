<?php

header("Access-Control-Allow-Origin: *"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Pragma: no-cache"); 

define('FM_SALES_FORSE_LOGIN','a773fb5f783645a4bd07a202090c54da');
define('FM_SALES_FORSE_PASSWORD','dB5FA53Bf97B4d818994f50491b5677A');

$file = 'send_to_ucrm.csv';

$json_ucrm = null;
  
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST["lead_source"])) { $lead_source = htmlspecialchars($_POST["lead_source"]); } else { $lead_source = ''; };
    if (isset($_POST["name"])) { $name = htmlspecialchars($_POST["name"]); } else { $name = ''; };
    if (isset($_POST["phone"])) { $phone = htmlspecialchars($_POST["phone"]); } else { $phone = ''; };
    if (isset($_POST["mse"])) { $mse = htmlspecialchars($_POST["mse"]); } else { $mse = ''; };


    switch ($lead_source) {
       
        case 'MSE_Duos_TRIN':
            $lead_source = 'MSE_TRIN_Duos';
            break;
        case 'MSE_Duos_TRY':
            $lead_source = 'MSE_TRY_Duos';
            break;
        case 'MSE_lil_TRIN':
            $lead_source = 'MSE_TRIN_lil';
            break;
        case 'MSE_lil_TRIN':
            $lead_source = 'MSE_TRIN_lil';
            break;
        case 'MSE_lil_TRY':
            $lead_source = 'MSE_TRY_Lil';
            break;
        case 'MSE_lil_BUY':
            $lead_source = 'MSE_BUY_LILSOLID';
            break;
    }
    
    $phone_lead = substr($phone,1);
    $city = 'Москва';
    $description = 'MSE код: '.$mse;
 
    $data_ucrm = [
      'BusinessEvent' => [
        '$type' => 'PMI.BDDM.Transactionaldata.RequestForLeadCreate',
        'Lead' => [
          'AcquisitionChannel' => [
            'Code' => $lead_source,
            'Name' => $lead_source
          ],
          'Source' => [
            'Code' => $lead_source,
            'CodeSpace' => 'RRPSF',
            'Name' => $lead_source
          ],
          'Person' => [
            'Code' => 'Draft',
            'CodeSpace' => 'uCRM'
          ],
          'Type' => 'Sale',
          'Status' => 'New',
          'Code' => 'Draft',
          'CodeSpace' => 'RRPSF',
          'Description' => $description,
          //'LeadReferralInput' => $bonusCardNumber,
          'RefferedBy' => [
            0 => [
              'Code' => '0',
              'CodeSpace' => '0'
            ]
          ],
          'Country' => [
            'Code' => 'RU',
            'CodeSpace' => 'MDM'
          ],
          'UpdateTime' => date('Y-m-d\TH:i:s.uP')
        ],
        'Person' => [
          'Code' => 'DRAFT',
          'Status' => 'DRAFT',
          'ContactInfo' => [
            'Name' => $name,
            'Surname' => 'test',
            //'Gender' => $gender,
            'PhoneNumbers' => [
              0 => [
                'Number' => $phone_lead,
                'Type' => 'Mobile'
              ]
            ],
            //'BirthDate' => $user_date,
            'Addresses' => [
              0 => [
                'Country' => 'Россия',
                'City' => $city
              ]
            ],
          ],
          'UpdateTime' => date('Y-m-d\TH:i:s.uP')
        ],
        "EventTime" => date('Y-m-d\TH:i:s.uP')
      ]
    ];
    

    $username = FM_SALES_FORSE_LOGIN;
    $password = FM_SALES_FORSE_PASSWORD;

    $headers  = [
        "Content-Type: application/json",
        "X-Content-Type-Options:nosniff",
        "Accept:application/json",
        "Cache-Control:no-cache"
    ];

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, "https://prd-lb.mulesoft-ch.pmruservice.com/lead-generation-ru/v2/api/leads");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($ch,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data_ucrm));
    curl_setopt($ch,CURLOPT_USERPWD,  $username . ":" . $password);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec ($ch);
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    curl_close($ch);  #Завершаем сеанс cURL
    
    file_put_contents($file, $result."\n", FILE_APPEND | LOCK_EX); // пишем лог
    file_put_contents($file, json_encode($data_ucrm)."\n", FILE_APPEND | LOCK_EX); // пишем лог
    
    echo print_r($result, true);

    return $result;
   
} else {
  header("HTTP/1.1 403 Forbidden");
  exit();
};
