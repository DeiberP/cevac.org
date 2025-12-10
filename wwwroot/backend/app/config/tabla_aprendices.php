<?php
include ('conection.php');

// Verificar conexión primero
if ($conection->connect_error) {
    die("Error de conexión: " . $conection->connect_error);
}

$sql = $conection->query("SELECT * FROM aprendices");

if ($sql === false) {
    die("Error en consulta de aprendices: " . $conection->error);
}

while ($row = $sql->fetch_assoc()) {
    // Verificar que el ID existe
    if (!isset($row['id']) || empty($row['id'])) {
        continue;
    }
    ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['nombres']; ?></td>
        <td><?php echo $row['apellidos']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo "(".$row['codigo_telefono'].") " . $row['telefono']; ?></td>
        <td>
            <a href="acciones/ver_aprendices.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Ver</a>
            <form method="post" style="display:inline;" action="acciones/eliminar_aprendiz.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="current_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php
}
?>
