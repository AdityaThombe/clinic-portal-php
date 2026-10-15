<?php
use Clinic\LabClient;
use Clinic\Patients;
use Clinic\Staff;
use PHPUnit\Framework\TestCase;

final class ClinicTest extends TestCase
{
    public function testSearchFindsMatchingPatient(): void
    {
        $db = Patients::connect();
        Patients::add($db, 'dr-house', 'Alice', 'flu');
        Patients::add($db, 'dr-house', 'Bob', 'cold');
        $this->assertSame([['name' => 'Alice', 'diagnosis' => 'flu']], Patients::search($db, 'dr-house', 'Alice'));
    }

    public function testSearchIsScopedToDoctor(): void
    {
        $db = Patients::connect();
        Patients::add($db, 'dr-house', 'Alice', 'flu');
        Patients::add($db, 'dr-grey', 'Alice', 'sprain');
        $this->assertCount(1, Patients::search($db, 'dr-grey', 'Alice'));
    }

    public function testCount(): void
    {
        $db = Patients::connect();
        Patients::add($db, 'dr-house', 'Alice', 'flu');
        $this->assertSame(1, Patients::count($db, 'dr-house'));
        $this->assertSame(0, Patients::count($db, 'dr-nobody'));
    }

    public function testStaffLogin(): void
    {
        $staff = new Staff();
        $staff->register('nurse.joy', 'healing-hands-42');
        $this->assertTrue($staff->login('nurse.joy', 'healing-hands-42'));
        $this->assertFalse($staff->login('nurse.joy', 'wrong'));
        $this->assertFalse($staff->login('nobody', 'healing-hands-42'));
    }

    public function testLabRequestIsSigned(): void
    {
        $request = LabClient::resultsRequest(7);
        $this->assertSame('{"patient":7}', $request['body']);
        $this->assertSame(64, strlen($request['signature']));
    }
}
