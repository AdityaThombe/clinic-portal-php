<?php
// Patient records. Demo target for ProofPatch: intentionally vulnerable. Never deploy this code.
namespace Clinic;

use PDO;

final class Patients
{
    public static function connect(): PDO
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('CREATE TABLE patients (id INTEGER PRIMARY KEY, doctor TEXT, name TEXT, diagnosis TEXT)');
        return $db;
    }

    public static function add(PDO $db, string $doctor, string $name, string $diagnosis): int
    {
        $stmt = $db->prepare('INSERT INTO patients (doctor, name, diagnosis) VALUES (?, ?, ?)');
        $stmt->execute([$doctor, $name, $diagnosis]);
        return (int) $db->lastInsertId();
    }

    /** A doctor's patients whose name matches the search box. */
    public static function search(PDO $db, string $doctor, string $name): array
    {
        $sql = "SELECT name, diagnosis FROM patients WHERE doctor = '" . $doctor . "' AND name = '" . $name . "'";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(PDO $db, string $doctor): int
    {
        $stmt = $db->prepare('SELECT COUNT(*) FROM patients WHERE doctor = ?');
        $stmt->execute([$doctor]);
        return (int) $stmt->fetchColumn();
    }
}
