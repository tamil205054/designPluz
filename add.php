<?php
require_once 'Connection.php';




$error_arr=array();
$first_name=$last_name=$phone=$email=$gender="";
$_cust_id=-1;
if(isset($_GET['id']))
{
    $_cust_id=$_GET['id'];
    $cust_query=mysqli_query($con,"SELECT * FROM `customer` where `id`=".$_cust_id)or die(mysqli_error($con));
    $arr=mysqli_fetch_array($cust_query);
    extract($arr);
}
if(isset($_POST['add']))
{
     
    extract($_POST);
    $rs=mysqli_query($con,
    "INSERT INTO `customer`(`id`, `first_name`, `last_name`, `phone`, `email`,`gender`) 
    VALUES (NULL,'".$first_name."','".$last_name."','".$phone."','".$email."',".$gender.")"
    )or die(mysqli_error($con).__LINE__);
    if($rs)
    {
        $cust_id=mysqli_insert_id($con);
        for($i=0;$i<count($address);$i++)
        {
            $primary=0;
            if(isset($isPrimary))
            {
                $primary=1;
            }
            mysqli_query($con,
            "INSERT INTO `address`(`s_no`,`id`, `cust_id`, `address_type`, `street_address`, `city`, `state`, `pincode`, `isPrimary`) 
            VALUES (".$i.",NULL,".$cust_id.",".$address[$i].",'".$street_address[$i]."','".$city[$i]."','".$state[$i]."','".$pincode[$i]."','".$primary."')")
            or die(mysqli_error($con).__LINE__);
        }
        $_SESSION['add_msg']="Customer Added Successfully";
        header('Location:index');
    }

}


if(isset($_POST['update']))
{
     
    extract($_POST);
    $rs=mysqli_query($con,
    "UPDATE `customer` SET `first_name`='".$first_name."',`last_name`='".$last_name."',`phone`='".$phone."',`gender`='".$gender."',`email`='".$email."' WHERE `id`=".$_cust_id)or die(mysqli_error($con).__LINE__);
    if($rs)
    {
        for($i=0;$i<count($address);$i++)
        {
            $primary=0;
            if($i==$isPrimary)
            {
                $primary=1;
            }
            if(isset($old_id[$i]))
            {
                mysqli_query($con,
                "UPDATE `address` SET `address_type`=".$address[$i].",`street_address`='".$street_address[$i]."',`city`='".$city[$i]."',`state`='".$state[$i]."',`pincode`='".$pincode[$i]."',`isPrimary`='".$primary."' WHERE `s_no`=".$i." and `cust_id`=".$_cust_id)
                or die(mysqli_error($con).__LINE__);
            }
            else
            {
                mysqli_query($con,
                "INSERT INTO `address`(`s_no`,`id`, `cust_id`, `address_type`, `street_address`, `city`, `state`, `pincode`, `isPrimary`) 
                VALUES (".$i.",NULL,".$_cust_id.",".$address[$i].",'".$street_address[$i]."','".$city[$i]."','".$state[$i]."','".$pincode[$i]."','".$primary."')")
                or die(mysqli_error($con).__LINE__);
            }
           
           
          
        }
        $_SESSION['update_msg']="Customer Updated Successfully";
        header('Location:index');
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Customer Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo BASE_URL?>/css/bootstrap.min.css">

</head>
<body>

  
<div class="container-fluid mt-5">
  <div class="row mt-5">
    <div class="col-md-10 mt-5" style="margin: auto;">
        <div class="card">
            <div class="card-body p-5">
                <h3 class="float-left"><?php echo ($_cust_id!=-1)? 'Edit' : 'Add'?> Customer</h3>
                
                <hr class="mt-5"/>
                <form method="POST">
                    <div class="form-group row">
                        <div class="col-md-6 col-12">
                            <label>First Name</label>
                            <input required type="text" value="<?php echo $first_name?>" class="form-control" name="first_name" />
                            
                        </div>
                        <div class="col-md-6 col-12">
                            <label>Last Name</label>
                            <input required type="text" value="<?php echo $last_name?>" class="form-control" name="last_name" />
                             
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Gender</label>
                            <select required class="form-control" name="gender">
                                <option value="-1">Select Gender</option>
                                <?php
                                
                                $gender_query=mysqli_query($con,
                                                "SELECT * FROM `gender_type` where `status`=1"
                                                )or die(mysqli_error($con).__LINE__);
                                while($type=mysqli_fetch_array($gender_query))
                                {
                                    ?>
                                        <option 
                                         <?php echo $type['id']==$gender ? 'selected="selected"' : '' ?>
                                        
                                        value="<?php echo $type['id']?>"><?php echo $type['type']?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Phone</label>
                            <input id="phone"  pattern="[0-9]{10}" required type="text" value="<?php echo $phone?>" class="form-control" name="phone" />
                            
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-md-6">
                            <label>Email</label>
                            <input id="email"   required type="email" value="<?php echo $email?>" class="form-control" name="email" />
                            
                        </div>
                    </div>
                    
                    <hr class="mt-5"/>
                    <h3>ADDRESS</h3>

                    <?php
                        $address_query=mysqli_query($con,
                                        "SELECT * FROM `address_type` where `status`=1"
                                        )or die(mysqli_error($con).__LINE__);
                    ?>
                      
                    <div  id="address_container">
                        <?php
                        $index=0;
                        if(isset($_cust_id))
                        {
                            $address_query=mysqli_query($con,"SELECT * FROM `address` where `cust_id`=".$_cust_id)or die(mysqli_error($con));
                            while($row1=mysqli_fetch_array($address_query))
                            {
                                $index++;
                                $primary="";
                                if($row1['isPrimary']==1)
                                {
                                    $primary="checked='checked'";
                                }

                                ?>
                                <div class="form-group row" id="con<?php echo $index-1?>">
                            <div class="col-md-2 col-12 mb-2" >
                                <input type="hidden" value="<?php echo $row1['id']?>" name="old_id[]" />
                                <select class="form-control" name="address[]" required>
                                    <option value="">address type</option>
                                    <?php
                                    $address_=mysqli_query($con,"SELECT * FROM `address_type` where `status`=1")or die(mysqli_error($con));
                                    
                                    while($row=mysqli_fetch_array($address_))
                                    {
                                        $selected="";
                                        if($row['id']==$row1['address_type'])
                                        {
                                            $selected="selected='selected'";
                                        }
                                        ?>
                                        <option <?php echo $selected?> value="<?php echo $row['id']?>"><?php echo $row['type']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>       
                            </div>
                            <div class="col-md-2 col-12 mb-2">
                                <input type="text" value="<?php echo $row1['street_address']?>" class="form-control" placeholder="Street Address" name="street_address[]" required />      
                            </div>
                            <div class="col-md-2 col-12 mb-2">
                                <input type="text" value="<?php echo $row1['city']?>" class="form-control" placeholder="City" name="city[]" required />      
                            </div>
                            
                            <div class="col-md-2 col-12 mb-2">
                                <input type="text" value="<?php echo $row1['state']?>"  class="form-control" placeholder="State" name="state[]" required />      
                            </div>
                            <div class="col-md-2 col-12 mb-2">
                                <input type="text" value="<?php echo $row1['pincode']?>"  pattern="[0-9]{4}" class="form-control" placeholder="Post code" name="pincode[]" required />      
                            </div>
                            <div class="col-md-2 col-12 mb-2">
                                <label><input type="radio" <?php echo $primary?> name="isPrimary" required value="<?php echo $index-1?>" /> Primary</label>
        <a href="javascript:void(0)" data-type="db" data-row-id="<?php echo $row1['id']?>" data-id="con<?php echo $index-1?>" class="text-danger remove float-right">X</a>
                                
                            </div>
                        </div>
                                <?php
                            }
                        }
                        
                        ?>
                    </div>
                    <a class="btn btn-info" onclick="ajax()">Add More</a>
                    <br>
                    <?php
                        
                        if(($_cust_id)!=-1)
                        {
                            ?>
                            <div class=" float-right">
                            <a href="index" class="btn btn-danger">Cancel</a>
                            <input type="submit" class=" btn btn-warning" name="update" value="Update" />
                            </div>
                           <?php
                        }
                        else
                        {
                            ?>
                              <div class=" float-right">
                            <a href="index" class="btn btn-danger">Cancel</a>
                            <input type="submit" class=" btn btn-success" name="add" value="Add New" />
                              </div>
                            <?php
                        }
                        
                        ?>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
<script src="<?php echo BASE_URL?>js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const rawRows=$("#address_container_hide").html();
let index=parseInt("<?php echo $index?>")

$(function(){
    $(document).on('click','.remove',function(){
        var data_type=$(this).attr('data-type')
        var data_id=$(this).attr('data-id')
        if(data_type=="local")
        {
            $("#"+data_id).remove();
            index--;
           

        }
        else
        {
            var row_id=$(this).attr('data-row-id')
            $("#"+data_id).remove();
            index--;
            $.ajax({
                url:"ajax/dynamic_row.php",
                method:"POST",
                data:{
                    get:"remove_row",
                    row_id:row_id,
                    cust_id:`<?php echo $_cust_id?>`
                },
                success:function(data)
                {
                    console.log(data)
                    if(data=="1")
                    {
                        location.reload();
                    }
                }
            })

        }
    })
});
<?php
    if(($_cust_id)==-1)
    {
        ?>
        ajax()

        <?php
    }
?>
function addRow(data)
{
    var list=(JSON.parse(data))
            var option=''
            for(let i=0;i<list.length;i++)
            {
                option+=`<option value="${list[i][0]}">${list[i][1]}</option>`;
            }
           var html=`
<div class="form-group row" id="con${index}" >
    <div class="col-md-2 col-12 mb-2">
        <select class="form-control" name="address[]" required>
            <option value="">address type</option>
                ${option}
        </select>       
    </div>
    <div class="col-md-2 col-12 mb-2">
        <input type="text"  class="form-control" placeholder="Street Address" name="street_address[]" required />      
    </div>
    <div class="col-md-2 col-12 mb-2">
        <input type="text"  class="form-control" placeholder="City" name="city[]" required />      
    </div>
    
    <div class="col-md-2 col-12 mb-2">
        <input type="text"  class="form-control" placeholder="State" name="state[]" required />      
    </div>
    <div class="col-md-2 col-12 mb-2">
        <input type="text"  pattern="[0-9]{4}" class="form-control" placeholder="Post code" name="pincode[]" required />      
    </div>
    <div class="col-md-2 col-12 mb-2">
        <label><input type="radio"  name="isPrimary" required value="${index}" /> Primary</label>
        <a href="javascript:void(0)" data-type="local" data-id="con${index}" class="text-danger remove float-right">X</a>
    </div>
</div>`;
index++;
$("#address_container").append(html)

}
function ajax()
{
    
    $.ajax({
        url:'ajax/dynamic_row.php',
        method:"POST",
        global:true,
        data:{
            get:"address_type"
        },
        success:function(data)
        {
            addRow(data)
        }
    })
    
}
</script>
</body>
</html>
