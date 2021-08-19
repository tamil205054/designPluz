<?php

require_once '../Connection.php';

if(isset($_POST['get']))
{
    if($_POST['get']=="address_type")
    {
        $address_query=mysqli_query($con,"SELECT `id`,`type` FROM `address_type` where `status`=1")or die(mysqli_error($con).__LINE__);
        $arr=mysqli_fetch_all($address_query);
        echo json_encode($arr);
    }
    if($_POST['get']=="remove_row")
    {
        $id=$_POST['row_id'];
        $qry=mysqli_query($con,"SELECT * FROM `address` where `id`=".$id)or die(mysqli_error($con));
        $isPrimary=0;
        $arr=mysqli_fetch_array($qry);
        $isPrimary=($arr['isPrimary']);
        $query=mysqli_query($con,"DELETE FROM `address` WHERE `id`=".$id)or die(mysqli_error($con));
        $qry=mysqli_query($con,"SELECT * FROM `address` where `cust_id`=".$_POST['cust_id'])or die(mysqli_error($con));
        $ind=0;
        $flag=0;
        while($row=mysqli_fetch_array($qry))
        {
            if($isPrimary)
            {
                mysqli_query($con,"UPDATE `address` SET `isPrimary`='1',`s_no` = ".$ind." WHERE `address`.`id` = ".$row['id'])or die(mysqli_error($con));
                $flag=1;
            }
            else
            {
                mysqli_query($con,"UPDATE `address` SET `s_no` = ".$ind." WHERE `address`.`id` = ".$row['id'])or die(mysqli_error($con));

            }
            $ind++;
            
        }
        echo $flag;
    }
}


?>