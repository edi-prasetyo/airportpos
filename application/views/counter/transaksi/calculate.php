<?php
// var_dump($store);
// die; 
?>



<?php
$message = "";
if (isset($_POST['SubmitButton'])) { //check if form was submitted
    $input = $_POST['inputText']; //get input text
    $message = "Success! You entered: " . $input;
}
?>

<form action="" method="post">
    <?php echo $message; ?>
    <input type="text" name="tanggal_jam" id="J-demo-02" class="form-control">
    <input type="submit" name="SubmitButton" />
</form>