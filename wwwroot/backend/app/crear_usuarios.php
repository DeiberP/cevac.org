<?php
$host = "localhost";
$db = "db_cevag";
$user = "usr_cevag";
$pass = "filtBdmkncuvxabq2[ye";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usuarios = [
        ["username" => "Gerente", "password" => "admin", "rol" => "admin"],
        ["username" => "Secretaria", "password" => "admin", "rol" => "admin"]
    ];

    foreach ($usuarios as $usuario) {
        $passwordHash = password_hash($usuario['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
        $stmt->execute([$usuario['username'], $passwordHash, $usuario['rol']]);
    }

    echo "Usuarios creados correctamente.";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
