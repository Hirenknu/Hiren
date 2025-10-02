<?php
if(!empty($success_msg)){
    foreach($success_msg as $msg){
        echo "<script>swal('Success!', '$msg', 'success');</script>";
    }
}
if(!empty($warning_msg)){
    foreach($warning_msg as $msg){
        echo "<script>swal('Oops!', '$msg', 'warning');</script>";
    }
}
if(!empty($error_msg)){
    foreach($error_msg as $msg){
        echo "<script>swal('Error!', '$msg', 'error');</script>";
    }
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>