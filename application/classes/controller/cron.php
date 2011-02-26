<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Controller {
  public function action_index() {
    $model = Model::factory('appointment');
    $appts = $model->select_all();

    $start = new DateTime();
    $start->add(DateInterval::createFromDateString('3 days'));
    $start = $start->getTimestamp();
    $end = new DateTime();
    $end = $end->add(DateInterval::createFromDateString('4 days'));
    $end = $end->getTimestamp();

    foreach($appts as $appt) {
      if ($appt['date'] > $start AND $appt['date'] < $end) {
        $this->sendReminder($appt);
      }
      else if ($appt['date'] > $end) {
        break;
      }
    }
  }

  private function sendReminder($appt) {
    $now = new DateTime();
    $fp = fopen("/home/brandonkliu/public_html/tthv/application/logs/reminders.txt", "a");
    $output = "Message: \"{$appt['message']}\" sent {$now->format("M j, Y")}";
    fwrite($fp, $output . "\n");
    echo $output;

    //$this->sendSMS($appt);
  }

  public function action_testsms() {
    $appt = Model::factory('appointment')->select_by_id(31); // Contains some kannada script
    
    $user="Vipashyin"; //your username
    $password="remindavax"; //your password
    $mobilenumbers="919731593584"; // Dr. Rashmi
    // $mobilenumbers="919448077487"; // Dr. Sudarshan
    //$message = urlencode($appt['message']);
    $message = $appt['message'];
    // $message = "Test message sent at " . strftime("%b %e %H:%M:%S"); //enter Your Message 
    echo $message;
    $senderid="SMSCountry"; //Your senderid
    $messagetype="OL"; //Unicode message
    $DReports="Y"; //Delivery Reports
    $url="http://www.smscountry.com/SMSCwebservice.asp";
    $message = urlencode($message);
    $ch = curl_init(); 
    if (!$ch){die("Couldn't initialize a cURL handle");}
    $ret = curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, 
      "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
    $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $curlresponse = curl_exec($ch); // execute
    if(curl_errno($ch))
      echo 'curl error : '. curl_error($ch);

    if (empty($ret)) {
      // some kind of an error happened
      die("curl error: " . curl_error($ch));
      curl_close($ch); // close cURL handler
    } 
    else {
      $info = curl_getinfo($ch);
      curl_close($ch); // close cURL handler
        //echo "<br>";
        echo "response: " . $curlresponse;    //echo "Message Sent Succesfully" ;

    }
  }

  private function sendSMS($appt) {
    $case = Model::factory('case')->select_by_id($appt['case_id']);
    $mobile = $case['mobile'];

    //copied from smscountry.com
    $user="Vipashyin"; //your username
    $password="13944943"; //your password
    $mobilenumbers=$mobile; //enter Mobile numbers comma seperated
    $message = $appt['message']; //enter Your Message 
    $senderid="SMSCountry"; //Your senderid
    $messagetype="N"; //Type Of Your Message
    $DReports="Y"; //Delivery Reports
    $url="http://www.smscountry.com/SMSCwebservice.asp";
    $message = urlencode($message);
    $ch = curl_init(); 
    if (!$ch){die("Couldn't initialize a cURL handle");}
    $ret = curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, 
      "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
    $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $curlresponse = curl_exec($ch); // execute
    if(curl_errno($ch))
      echo 'curl error : '. curl_error($ch);

    if (empty($ret)) {
      // some kind of an error happened
      die(curl_error($ch));
      curl_close($ch); // close cURL handler
    } 
    else {
      $info = curl_getinfo($ch);
      curl_close($ch); // close cURL handler
        //echo "<br>";
        echo $curlresponse;    //echo "Message Sent Succesfully" ;

    }
  }

}
