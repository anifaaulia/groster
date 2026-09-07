<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public array $results = [
        'created'  => 0,
        'skipped'  => 0,
        'errors'   => [],
    ];

    private static array $validRoles = ['admin', 'pic', 'participant'];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because row 1 is heading

            $name     = trim($row['nama']     ?? '');
            $email    = trim($row['email']    ?? '');
            $password = trim($row['password'] ?? '');
            $instName = trim($row['instansi'] ?? '');
            $role     = strtolower(trim($row['peran'] ?? ''));

            if (empty($name) && empty($email)) {
                continue;
            }

            // Validate required fields
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $this->results['errors'][] = "Baris {$rowNum}: kolom nama/email/password/peran tidak boleh kosong.";
                continue;
            }

            // Validate role
            if (!in_array($role, self::$validRoles)) {
                $this->results['errors'][] = "Baris {$rowNum} ({$email}): peran '{$role}' tidak valid. Gunakan: admin, pic, atau participant.";
                continue;
            }

            // Skip if email already exists
            if (User::where('email', $email)->exists()) {
                $this->results['skipped']++;
                continue;
            }

            // Find institution
            $institution = null;
            if (!empty($instName)) {
                $institution = Institution::whereRaw('LOWER(name) = ?', [strtolower($instName)])->first();
                if (!$institution) {
                    $this->results['errors'][] = "Baris {$rowNum} ({$email}): instansi '{$instName}' tidak ditemukan.";
                    continue;
                }
            }

            $user = User::create([
                'name'           => $name,
                'email'          => $email,
                'password'       => Hash::make($password),
                'institution_id' => $institution?->id,
            ]);

            $user->assignRole($role);
            $this->results['created']++;
        }
    }
}
