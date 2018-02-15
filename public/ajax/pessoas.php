<?php

run_forever();
show_errors();
$data = [];
foreach (range(1, 200000) as $d) {
    $data[] = '[' . $d . ',"123456789"]';
}
echo '{"data":[' . implode(',', $data) . ']}';

