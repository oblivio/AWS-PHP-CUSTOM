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

//Define Variables and their default values:
$aws_namespace = 'AWS/Billing';
$aws_metric = 'EstimatedCharges';
$aws_query_start = strtotime('-14 days');
$aws_query_end = strtotime('now');
$aws_query_period = 86400;
$aws_desired_statistics = array( //required :: The metric statistics to return. 
            'Maximum',
            'Average',
            'Minimum',
            'Sum',
            'SampleCount'
    );
//Override them with $aws_request if available
if(!empty($aws_request['aws_namespace'])){
    $aws_namespace = $aws_request['aws_namespace'];
}
if(!empty($aws_request['aws_metric'])){
    $aws_metric = $aws_request['aws_metric'];
}
if(!empty($aws_request['aws_metric'])){
	$aws_service = $aws_request['aws_metric'];
}
if(!empty($aws_request['aws_query_start'])){
    $aws_query_start = $aws_request['aws_query_start'];
}
if(!empty($aws_request['aws_query_end'])){
    $aws_query_end = $aws_request['aws_query_end'];
}
if(!empty($aws_request['aws_query_period'])){
    $aws_query_period = $aws_request['aws_query_period'];
}
if(!empty($aws_request['aws_desired_statistics'])){
    $aws_desired_statistics = explode("&",$aws_request['aws_desired_statistics']);
}

$getMetricStatisticsConfig = array(
  // Namespace is required
    'Namespace' => $aws_namespace,
    // MetricName is required
    'MetricName' => $aws_metric,
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
    'StartTime'  => $aws_query_start, //required
    'EndTime'    => $aws_query_end, //required
    'Period'     => $aws_query_period, //required :: The granularity, in seconds, of the returned datapoints.
    'Statistics' => $aws_desired_statistics, 
    //'Unit' => 'string [Seconds | Microseconds | Milliseconds | etc... ', //not required
);
if(!empty($aws_service)){
	$getMetricStatisticsConfig['Dimensions'][] = array("Name"=>"ServiceName","Value"=>$aws_service);
}
$result = $client->getMetricStatistics($getMetricStatisticsConfig);

$json_result = json_encode(array(
                    "Datapoints" => $result->get('Datapoints'),
                    "Label" => $result->get('Label')
                ));

echo $json_result;


