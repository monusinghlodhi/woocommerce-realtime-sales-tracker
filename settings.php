<?php
if(isset($_POST['firebasedatabasesettingsavebtn'])){
    update_option("firebase_database_url",$_POST['firebasedatabaseurl']);
    update_option("firebase_database_api_key",$_POST['firebasedatabaseapikey']);

}
?>

<div class="container">
    <div class="row">
        <div class="col-8">
        <h2>Woocommerce Firebase Realtime Database Settings</h2>
        <small>Enter Firebase Database URL and API Key.</small>
        <form action="" method="POST">
            <div class="form-group">
                <label for="firebasedatabaseurl">Firebase Realtime Database URL:</label>
                <input type="text" class="form-control" id="firebasedatabaseurl" name="firebasedatabaseurl" value="<?php echo get_option("firebase_database_url") ?>">
            </div>
            <div class="form-group">
                <label for="firebasedatabaseapikey">Firebase Realtime Database secret key:</label>
                <input type="text" class="form-control" id="firebasedatabaseapikey" name="firebasedatabaseapikey" value="<?php echo get_option("firebase_database_api_key") ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="firebasedatabasesettingsavebtn">Save</button>
        </form>
        </div>
    </div>
</div>
