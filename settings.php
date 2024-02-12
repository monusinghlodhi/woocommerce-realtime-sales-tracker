<?php
if(isset($_POST['firebasedatabasesettingsavebtn'])){
    update_option("firebase_database_url",$_POST['firebasedatabaseurl']);
    update_option("firebase_database_api_key",$_POST['firebasedatabaseapikey']);
    update_option("firebase_summery_email_id",$_POST['firebasesummerysendemail']);
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
            <div class="form-group">
                <label for="firebasesummerysendemail">Email ID (for receiving daily sales summaries report on email.):</label>
                <!-- <input type="text" class="form-control" id="firebasesummerysendemail" name="firebasesummerysendemail" value="<?php //echo get_option("firebase_summery_email_id") ?>"> -->
                <input type="text" class="form-control" id="firebasesummerysendemail" name="firebasesummerysendemail" value="<?php echo esc_attr( get_option( 'firebase_summery_email_id', get_option( 'admin_email' ) ) ); ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="firebasedatabasesettingsavebtn">Save</button>
        </form>
        </div>
    </div>
</div>
