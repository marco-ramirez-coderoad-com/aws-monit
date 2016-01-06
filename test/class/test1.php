<?php

/**
 * Created by IntelliJ IDEA.
 * User: mramirez-laptop
 * Date: 10/19/2015
 * Time: 11:51 AM
 */

$key = $_GET['key'];
$secret = $_GET['secret'];
$region = $_GET['region'];
$id = $_GET['id'];

$secret2 = urldecode($secret);

require '../aws/aws-autoloader.php';
use Aws\Ec2\Ec2Client;
    
$ec2Client=Ec2Client::factory([
    'region' => $region,
    'version' => '2015-10-01',
    'credentials' =>[
        'key' => $key,
        'secret' => $secret2
    ],
    'scheme' => 'http'
]);

$result = $ec2Client->describeInstances(['Filters' => [['Name' => "instance-id" , 'Values' => ["$id"]]]]);
$flag1=$result['Reservations'][0]['Instances'][0]['State']['Name'];
if ($flag1 == 'running'){
    echo ('estaba prendido');
    $result = $ec2Client->stopInstances(['InstanceIds' => [$id,],]);
}
else {
    echo ('estaba apagado');
    $result = $ec2Client->startInstances(['InstanceIds' => [$id,],]);
}
echo($result['Reservations'][0]['Instances'][0]['State']['Name']);
?>