<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    echo "O teu nome é: " . $nome;
} else {
    echo "Non se enviou ningún dato.";
}
?>
