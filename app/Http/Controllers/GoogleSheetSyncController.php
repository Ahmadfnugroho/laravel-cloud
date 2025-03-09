<?php

namespace App\Http\Controllers;

use App\Http\Services\GoogleSheetsServices;
use App\Models\User;
use App\Models\UserPhoneNumber;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class GoogleSheetSyncController
{
    public function sync(Request $request)
    {
        try {
            $data = $request->json()->all();

            if (!isset($data['values']) || count($data['values']) < 2) {
                return response()->json(['error' => 'No data found'], 400);
            }

            $headers = array_map(fn($header) => trim($header), $data['values'][0]);
            $rows = array_slice($data['values'], 1);

            DB::transaction(function () use ($headers, $rows) {
                foreach ($rows as $row) {
                    $rowData = array_combine($headers, array_pad($row, count($headers), null));

                    // Pastikan email tersedia sebelum melanjutkan
                    if (empty($rowData['Email Address'])) {
                        continue;
                    }

                    $user = User::updateOrCreate(
                        ['email' => $rowData['Email Address']],
                        [
                            'name' => $rowData['Nama Lengkap (Sesuai KTP)'] ?? null,
                            'address' => $rowData['Alamat Tinggal Sekarang (Ditulis Lengkap)'] ?? null,
                            'job' => $rowData['Pekerjaan'] ?? null,
                            'office_address' => $rowData['Alamat Kantor'] ?? null,
                            'instagram_username' => $rowData['Nama akun Instagram penyewa'] ?? null,
                            'emergency_contact_name' => $rowData['Nama Kontak Emergency'] ?? null,
                            'emergency_contact_number' => $rowData['No. Hp Kontak Emergency'] ?? null,
                            'gender' => $rowData['Jenis Kelamin'] ?? null,
                            'source_info' => $rowData['Mengetahui Global Photo Rental dari'] ?? null,
                            'status' => $rowData['STATUS'] ?? 'Aktif',
                            'password' => Hash::make('defaultpassword')
                        ]
                    );

                    // Simpan nomor telepon
                    $phoneNumbers = array_filter([$rowData['No. Hp1'] ?? null, $rowData['No. Hp2'] ?? null]);
                    foreach ($phoneNumbers as $phone) {
                        UserPhoneNumber::updateOrCreate(
                            ['user_id' => $user->id, 'phone_number' => $phone],
                            ['user_id' => $user->id, 'phone_number' => $phone]
                        );
                    }
                }
            });

            return response()->json(['message' => 'Data synchronized successfully']);
        } catch (Exception $e) {
            Log::error('Google Sheet Sync Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
