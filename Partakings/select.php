<?php

// namespace and class import declaration

use DBC\DBC;

// script import declaration

require_once '../DBC/DBC.php';
include_once './Partakings.php';

$id_scientific_papers = $_GET['id_scientific_papers'];

// if id of record was interpolated into URL query string 
if (isset($id_scientific_papers)) {
    // instantiate a PDO object to retrieve database connection  
    $DBC = new DBC();
    // select partakers
?>
    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Soavtor</th>
                    <th>Indeks</th>
                    <th>Vloga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($DBC->selectSciPapPartakers($id_scientific_papers) as $partaker) {
                ?>
                    <tr>
                        <td>
                            <?php
                            // if partaker student has an account 
                            if ($DBC->checkStudentAccount($DBC->selectStudentsByIndex($partaker->index)[0]->id_attendances)) {
                                // if partaker student has an account avatar
                                if ($avatar = $DBC->hasAcctAvatar($partaker->index)) {
                            ?>
                                    <img class="acct-avtr" src="<?php echo "/eArchive/{$avatar}"; ?>" alt="Avatar">
                            <?php
                                } // if
                            }
                            ?>
                            <?php echo $partaker->fullname; ?>
                        </td>
                        <td><?php echo $partaker->index; ?></td>
                        <td><?php echo $partaker->getPart(); ?></td>
                    </tr>
                <?php
                } // foreach
                ?>
            </tbody>
        </table>
    </div>
<?php
} // if

?>