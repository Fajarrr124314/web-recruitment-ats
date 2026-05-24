<?php
$files = [
    'c:\laragon\www\web-rekruitmen\resources\views\components\layouts\app.blade.php',
    'c:\laragon\www\web-rekruitmen\resources\views\livewire\auth\login.blade.php'
];

foreach($files as $f){
    $c = file_get_contents($f);
    $c = str_replace('indigo', 'red', $c);
    $c = str_replace('violet', 'rose', $c);
    file_put_contents($f, $c);
}
echo "Done";
