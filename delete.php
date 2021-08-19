<?php

include_once 'Connection.php';


if(isset($_GET['id']))
{
    mysqli_query($con,"DELETE FROM `customer` WHERE `id`=".$_GET['id'])or die(mysqli_error($con));
    mysqli_query($con,"DELETE FROM `address` WHERE `cust_id`=".$_GET['id'])or die(mysqli_error($con));
    $_SESSION['delete_msg']="Customer Deleted Successfully";
    header('Location:index');

}


?>