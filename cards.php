<?php
// Include config file
require_once "core/config.php";
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$result = mysqli_query($connection, 'SELECT * from trips');

while ($trips =  mysqli_fetch_assoc($result)) {
    if ($trips["trip_active"] === '1') {
        ?>
        <div class="col-sm-4 mb-4">
            <div class="card">
            <img src='<?php print($trips["trip_pic"]);?>' class="card-img-top" 
                 alt="<?php print($trips["trip_name"]);?>">
                <div class="card-body">
                    <h5 class="card-title"><?php print($trips["trip_name"]);?></h5>
                    <p class="card-text"><?php print($trips["trip_shortdesc"]);?></p>
                    <a href="#" class="btn btn-primary">Érdekel</a>
                </div>
            </div>
        </div>
<?php  } else {
        continue;
    }
}