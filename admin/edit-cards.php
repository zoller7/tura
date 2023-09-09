<?php
// Include config file
require_once "../core/config.php";
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// trips table datas
$result = mysqli_query($connection, 'SELECT * from trips');

// List of the trips and add edit button
while ($trips =  mysqli_fetch_assoc($result)) {?>

  <tbody>
    <?php print ($trips["trip_active"]) ? "<tr class=\"table-light\">" : "<tr class=\"table-danger\">"; ?>
      <th scope="row"><?php print($trips["trip_id"]);?></th>
      <td>
        <?php
            if ($trips["trip_active"] === '1') {
                print("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-check-circle\" viewBox=\"0 0 16 16\">
                <path d=\"M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z\"/>
                <path d=\"M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z\"/>
              </svg>");
            } else {
                print("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-circle\" viewBox=\"0 0 16 16\">
                <path d=\"M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z\"/>
                <path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>
              </svg>");
            }
        ?>
      </td>
      <td><?php print($trips["trip_name"]);?></td>
      <td><?php print($trips["trip_checkpoints"]);?></td>
      <td><img src="<?php print($trips["trip_pic"]);?>" width="30%"></td>
      <td><?php print($trips["trip_shortdesc"]);?></td>
      <td>
        <a href="edit-trip.php?id=<?php print($trips["trip_id"]);?>" class="btn btn-secondary btn-sm" tabindex="-1" role="button" aria-disabled="true">Szerkeszt</a>
      </td>
    </tr>
  </tbody>
<?php }
