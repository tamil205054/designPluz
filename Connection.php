<?php

    define('HOST','localhost');
    define('DB_NAME','design_pluz');
    define('DB_USER','root');
    define('DB_PASSWORD','');
    define('BASE_URL','https://localhost/designpluz/');
    $con = mysqli_connect(HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('database connection error');

    session_start();
    function greetings($session_type="add_msg",$msg="success")
    {
        if(isset($_SESSION[$session_type]))
        {
        ?>
        <div class="alert alert-<?php echo $msg?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION[$session_type]?>
         
        </div>
        <?php
        unset($_SESSION[$session_type]);
        }
            
    }

    function error($index,$arr)
    {
        if(isset($arr[$index]))
        {
            ?>
            <strong><?php echo $arr[$index]?></strong>
            <?php
        }
    }
?>