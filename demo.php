<?php

require 'aws-php/aws-autoloader.php';

use Aws\CloudWatch\CloudWatchClient;

/* 
    This demo assumes that the VM has an IAM role assigned.
*/

$region = "custom-region";
/*
 * $ssl_ca is the path to a .cer file containing the CA chain for
 * the desired end-point. To find out what the CA chain, you have
 * to enter the following commands on the *NIX terminal:
 * 1.) openssl s_client -connect CUSTOM_END_POINT:443
 * 2.) Based on the output, export each of these CA's as
 * 		a base64 .cer file. 
 * 3. Combine all the individual .cer files into a single
 * 		.cer file by copy pasting the individual file content.
 * 4.)Move the .cer path to /home/[username]/certs 
 * 		**certs folder does not exist it must be created
 * 5.)$ssl_ca = "/home/userA/certs/combined.cer";
 */
$ssl_ca = "/path/to/combined.cer";

//CloudWatch :: Documentation for AWS PHP SDK
//http://docs.aws.amazon.com/aws-sdk-php/guide/latest/service-cloudwatch.html

$client = CloudWatchClient::factory(array(
		'region'  => $region,
		'ssl.certificate_authority'=>$ssl_ca
));

$result = $client->getMetricStatistics(array(
  // Namespace is required
    'Namespace' => 'AWS/Billing',
    // MetricName is required
    'MetricName' => 'EstimatedCharges',
    'Dimensions' => array(
    		array(
    			//Currency is required;
    			'Name' => 'Currency',
    			//
    			'Value' => 'USD',
    		),
    		/*array(
	            // ServiceName is not required;
	            'Name' => 'ServiceName',
	            // 
	            'Value' => '',
	        ),
	        array(
	            // LinkedAccount is not required;
	            'Name' => 'LinkedAccount',
	            // 
	            'Value' => '',
	        ),*/
    	),
    'StartTime'  => strtotime('-7 days'), //required
    'EndTime'    => strtotime('now'), //required
    'Period'     => 86400, //required :: The granularity, in seconds, of the returned datapoints.
    'Statistics' => array( //required :: The metric statistics to return. 
    		'Maximum',
    		'Average',
    		'Minimum',
    		'Sum',
    		'SampleCount'
    ), 
    //'Unit' => 'string [Seconds | Microseconds | Milliseconds | etc... ', //not required
));

print_r($result);


?>

