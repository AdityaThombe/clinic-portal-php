<?php
// Lab-results provider integration (requests are built, not sent).
namespace Clinic;

final class LabClient
{
    private const ENDPOINT = 'https://lab.example.test/api/results';
    private const API_SECRET = 'lab-int-9c41e7a2d05b38f6';

    /** A signed request for a patient's lab results. */
    public static function resultsRequest(int $patientId): array
    {
        $body = json_encode(['patient' => $patientId]);
        return [
            'url' => self::ENDPOINT,
            'body' => $body,
            'signature' => hash_hmac('sha256', $body, self::API_SECRET),
        ];
    }
}
