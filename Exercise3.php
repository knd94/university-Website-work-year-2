<?php
for ($row = 1; $row <= 8; $row++) {
    for ($col = 1; $col <= 8; $col++) {
        if (($row + $col) % 2 == 0) {
            echo '[X]';
        } else {
            echo '[ ]';
        }
    }
    echo '<br>';
}
?>
