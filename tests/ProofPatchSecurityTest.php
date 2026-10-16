<?php
declare(strict_types=1);

use Clinic\Patients;
use PHPUnit\Framework\TestCase;

final class ProofPatchSecurityTest extends TestCase
{
    private function createDb(): PDO
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('CREATE TABLE patients (name TEXT, diagnosis TEXT, doctor TEXT)');
        $db->exec("INSERT INTO patients (name, diagnosis, doctor) VALUES ('Alice','Flu','evil')");
        $db->exec("INSERT INTO patients (name, diagnosis, doctor) VALUES ('Bob','Cold','good')");
        return $db;
    }

    public function testSqlInjectionIsPrevented(): void
    {
        require_once __DIR__ . '/../src/Patients.php';
        $db = $this->createDb();
        $result = Patients::search($db, "evil' OR 1=1 -- ", 'Alice');
        $this->assertEmpty($result);
    }

    public function testValidSearchReturnsRow(): void
    {
        require_once __DIR__ . '/../src/Patients.php';
        $db = $this->createDb();
        $result = Patients::search($db, 'evil', 'Alice');
        $this->assertCount(1, $result);
        $this->assertSame('Alice', $result[0]['name']);
    }
}
