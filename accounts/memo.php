<link href="../css/style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../css/font-awesome.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
function display(Iid){
    alert(Iid);
    var string1 = 'WEl3HBftdqSGkEWxyhybflcuerkoW6a8WkP5VZ6w';
    var encoded = encodeURIComponent(string1);
    $.ajax({
        url: "../class/test1.php",
        method: "GET",
        statusCode: {
            404: function() {
                alert( "page not found" );
            }
        },
        data: {
            key : 'AKIAI7PERUOF7UHLUZQQ',
            secret : encoded,
            region : 'us-east-1',
            id : Iid,
        },
        success : function(result){
            console.debug(result);
            alert(result);
            if (result == 'running') {
                alert('runing');
            }
        }
    }).done(function(){
        alert('Listo');
    });
}
</script>
<?php
$i=1;
require '../aws/aws-autoloader.php';
use Aws\Ec2\Ec2Client;

$region='us-east-1';
$key='AKIAI7PERUOF7UHLUZQQ';
$secret='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';
error_reporting(1);

$ec2Client=Ec2Client::factory([
    'region' => $region,
    'version' => '2015-10-01',
    'credentials' =>[
        'key' => $key,
        'secret' => $secret
    ],
    'scheme' => 'http'
]);
?>

<form>
<table id='instance-cont'>
<?php
$result2 = $ec2Client->DescribeInstances();
$reservations = $result2['Reservations'];
foreach ($reservations as $reservation) {
    $instances = $reservation['Instances'];
    foreach ($instances as $instance) {
        $instanceName = '';
        foreach ($instance['Tags'] as $tag) {
            if ($tag['Key'] == 'Name') {
                $instanceName = $tag['Value'];
            }
        }
?>
            <tr>
                <td class='Iname'><?php echo $instanceName;?></td>
                <td class='IState'>
<?php
    if ($instance['State']['Name']=='running' || $instance['State']['Name']=='pending') { echo "<img src='../images/green.jpg'></td>"; $switch='checked';}
    elseif ($instance['State']['Name']=='stopped' || $instance['State']['Name']=='stopping') { echo "<img src='../images/red.png'></td>";$switch='';}

?>                
                <td class='IID'><?php echo $instance['InstanceId'];?></td>
                <td class='IDNS'><?php echo $instance['PrivateDnsName'];?></td>
                <td class='IControl'><div id='control1'>
                        <div id='cron1'>
                            <label for='cron' style='padding-left:5px'>CRON : </label><input id='cron' type='text' />
                        </div>
                        <div class='onoff'>
                           <input type='checkbox' value='None' class='onoff2' name='check<?php echo $i; ?>' <?php echo $switch; ?> onclick='javascript:display("<?php echo $instance['InstanceId'];?>")' />
    	                   <label><i class='icon-off'></i></label>
                        </div>
                        <div class='action' id='button2'>Action</div>
                </div></td>
            </tr>
<?php
        $i++;
    }
}
echo "</table></form>";
?>