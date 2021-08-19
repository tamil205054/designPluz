<?php
require_once 'Connection.php';


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
                <h3 class="float-left">Customers</h3>
                <div class="float-right">
                    <a class="btn btn-info btn-sm" href="<?php echo BASE_URL?>add">Add Customer</a>
                </div>
                <hr class="mt-5"/>
                <div class="table-responsive ">
                    <?php
                        greetings('add_msg','success');
                        greetings('update_msg','warning');
                        greetings('delete_msg','danger');
                    ?>
                    <table class="table table-bordered ">
                        <thead class="thead-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $cust_query=mysqli_query($con,
                                        "SELECT * FROM `customer` where `status`=1"
                                        )or die(mysqli_error($con).__LINE__);
                            
                            if(mysqli_num_rows($cust_query)==0)
                            {
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center text-info"><b>Customer not Found!!!</b></td>
                                </tr>
                                <?php
                            }
                            else
                            {
                                while($customer=mysqli_fetch_array($cust_query))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo ucfirst($customer['first_name'])?></td>
                                        <td><?php echo ucfirst($customer['last_name'])?></td>
                                        <td><?php echo $customer['phone']?></td>
                                        <td><?php echo $customer['email']?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL?>add?id=<?php echo $customer['id']?>">Edit</a>
                                            <a onclick="return confirm('Do you want delete <?php echo $customer['first_name']?>')" href="<?php echo BASE_URL ?>delete?id=<?php echo $customer['id']?>">Delete</a>

                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<script src="<?php echo BASE_URL?>js/bootstrap.min.js"></script>
</body>
</html>
