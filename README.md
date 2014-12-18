AWS-PHP-CUSTOM
==============

AWS PHP SDK modified to for interaction with custom endpoints and regions.

Config files that need to be modified for custom-endpoint functionality:
1.) aws-php/Aws/Common/Resources/custom-endpoints.php 
2.) aws-php/Aws/CloudWatch/Resources/cloudwatch-custom-regions.php

New files to enable functionality:
1.) aws-php/Aws/CloudWatch/Resources/cloudwatch-custom.php
2.) aws-php/Aws/Common/Resources/custom-endpoints.php 
3.)	aws-php/Aws/CloudWatch/Resources/cloudwatch-custom-regions.php

Modified files to enable functionality:
1.) aws-php/Aws/CloudWatch/CloudWatchClient.php [const LATEST_API_VERSION = 'custom']
2.) aws-php/Aws/Common/Resources/public-endpoints.php


