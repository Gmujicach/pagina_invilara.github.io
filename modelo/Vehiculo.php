<?php

class Vehiculo
{
    private $conn;

    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    public function crearVechiculo(
        $serial,
        $numero_vehiculo,
        $color,
        $ID_tipo,
        $ID_fabricante,
    ): bool {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO `vehiculo` (
                `serial`,
                `numero_vehiculo`,
                `color`,
                `ID_tipo`,
                `ID_fabricante`
                ) VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $serial,
                $numero_vehiculo,
                $color,
                $ID_tipo,
                $ID_fabricante,
            ]);
            return true;
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
            exit;
        }
    }

    public function actualizarVehiculo(
        $id_serial,
        $serial,
        $numero_vehiculo,
        $color,
        $ID_tipo,
        $ID_fabricante,
    ): bool {
        if (!$this->obtenerVehiculo($id_serial)) {
            echo "ERROR: El conductor con la cedula . $id_serial . no existe";
            exit;
        }

        $stmt = $this->conn->prepare(
            "UPDATE 
                `vehiculo` 
            SET 
                `serial` = ?, 
                `numero_vehiculo` = ?, 
                `color` = ?, 
                `ID_tipo` = ?, 
                `ID_fabricante` = ? 
            WHERE 
                `serial` = ?"
        );
        return $stmt->execute([
            $serial,
            $numero_vehiculo,
            $color,
            $ID_tipo,
            $ID_fabricante,
            $id_serial,
        ]);
    }

    public function eliminarVehiculo($serial)
    {
        $stmt = $this->conn->prepare("DELETE FROM `vehiculo` WHERE serial=?");
        $stmt->execute([$serial]);
        return $stmt->rowCount();
    }

    /**
     * Listara todos los vehiculos
     * @return array
     */
    public function listarVehiculos(): array
    {
        $result = $this->conn->query("SELECT * FROM `vehiculo`");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Listara todos los fabricantes
     * @return array
     */
    public function listarFabricantes(): array
    {
        $result = $this->conn->query("SELECT * FROM `fabricante`");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Listara todos los tipos de vehiculos
     * @return array
     */
    public function listarTiposVehiculos()
    {
        $result = $this->conn->query("SELECT * FROM `tipovehiculo`");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVehiculo($id): array | bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM `vehiculo` WHERE `serial` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
