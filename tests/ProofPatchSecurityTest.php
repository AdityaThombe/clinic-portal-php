<?php
declare(strict_types=1);

use Clinic\Patients;
use PHPUnit\Framework\TestCase;

final class ProofPatchSecurityTest extends TestCase
{
    private \PDO $db;

    protected function setUp(): void
    {
        $this->db = new \PDO('sqlite::memory:');
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->exec("CREATE TABLE patients (doctor TEXT, name TEXT, diagnosis TEXT);");
        $this->db->exec("INSERT INTO patients (doctor, name, diagnosis) VALUES ('DrSmith', 'John Doe', 'Flu');");
    }

    public function testNormalSearchReturnsRow(): void
    {
        $result = Patients::search($this->db, 'DrSmith', 'John Doe');
        $this->assertCount(1, $result);
    }

    public function testSqlInjectionDoesNotLeak(): void
    {
        // Attempt to inject "OR 1=1" to retrieve all rows.
        $maliciousDoctor = "DrSmith' OR 1=1 -- ";
        $result = Patients::search($this->db, $maliciousDoctor, 'John Doe');
        // Injection should not return any rows because the query is safely parameterized.
        $this->assertCount(0, $result);
    }
}
