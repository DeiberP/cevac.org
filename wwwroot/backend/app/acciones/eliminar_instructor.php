<?php
include("../config/conection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $success = true;
    $error = null;

    $conection->begin_transaction();

    try {
/*Aqui se elimina el postulante*/
        $stmt_aprendiz = $conection->prepare("DELETE FROM instructores WHERE id = ?");
        $stmt_aprendiz->bind_param("i", $id);

        if (!$stmt_aprendiz->execute()) {
            throw new Exception("Error al eliminar instructor: " . $stmt_aprendiz->error);
        }
        
        $conection->commit();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {

        $conection->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    if (isset($stmt_aprendiz)) {
        $stmt_aprendiz->close();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado.']);
}

$conection->close();
?>