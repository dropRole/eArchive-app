<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
require_once './Attendances.php';

$id_attendances = $_GET['id_attendances'];

// if id of a students program attendance if properly forwarded by URL query string 
if (isset($id_attendances)) {
    // instantiate a PDO object carrying database connection
    $DBC = new DBC();
    // select program attendance particulars
    $particulars = $DBC->selectProgAttnParticulars($id_attendances);
?>
    <div class="d-flex flex-column justify-content-start">
        <div class="align-self-center">
            <?php
            // if the subject student has been assigned with an account 
            if ($DBC->checkStudtAcct($id_attendances))
                // if student has an account avatar
                if ($avatar = $DBC->hasAcctAvatar($particulars->index)) {
            ?>

                <img class="acct-avtr-big" src="<?php echo "/eArchive/{$avatar}"; ?>" alt="Avatar">
            <?php
                }
            ?>
            <p class="text-center">
                <span class="text-info"><?php echo $particulars->email; ?></span><br>
            </p>
        </div>
        <div class="align-self-start table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td scope="row" class="text-info">Indeks</td>
                        <td><?php echo $particulars->index; ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-info">Vpisan</td>
                        <td><?php echo (new DateTime($particulars->enrolled))->format('d-m-Y'); ?></td>
                    </tr>
                    <?php
                    // if student graduated on the program
                    if ($particulars->defended != NULL) {
                    ?>
                        <tr>
                            <td scope="row" class="text-info">Dimplomiral</td>
                            <td><?php echo (new DateTime($particulars->defended))->format('d-m-Y'); ?></td>
                        </tr>
                    <?php
                    } // if
                    ?>
                    <tr>
                        <td scope="row" class="text-info">Program</td>
                        <td><?php echo $particulars->program; ?></td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-info">Fakuleta</td>
                        <td><?php echo $particulars->faculty; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php
} // if
?>