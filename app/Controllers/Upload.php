<?php

namespace App\Controllers;

class Upload extends BaseController
{
    public function uploadImage()
    {
        // Set JSON header
        header('Content-Type: application/json');

        try {
            // Debug: Log request info
            log_message('info', 'Upload request received');
            log_message('info', 'Files: ' . json_encode($_FILES));

            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['error' => 'Tidak ada file yang diupload atau terjadi error']);
                return;
            }

            $file = $_FILES['file'];

            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedTypes)) {
                echo json_encode(['error' => 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF yang diperbolehkan.']);
                return;
            }

            // Validasi ukuran file (maksimal 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                echo json_encode(['error' => 'Ukuran file terlalu besar. Maksimal 5MB.']);
                return;
            }

            // Generate nama file unik
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '.' . $extension;

            // Path upload
            $uploadPath = FCPATH . 'uploads/soal/';

            // Debug: Log path info
            log_message('info', 'Upload path: ' . $uploadPath);
            log_message('info', 'File name: ' . $fileName);
            log_message('info', 'FCPATH: ' . FCPATH);

            // Buat folder jika belum ada
            if (!is_dir($uploadPath)) {
                log_message('info', 'Creating directory: ' . $uploadPath);
                if (!mkdir($uploadPath, 0777, true)) {
                    log_message('error', 'Failed to create directory: ' . $uploadPath);
                    echo json_encode(['error' => 'Gagal membuat folder upload']);
                    return;
                }
            }

            // Pindahkan file
            $targetFile = $uploadPath . $fileName;
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                // Set permissions
                chmod($targetFile, 0644);

                // Return URL gambar yang benar
                $imageUrl = rtrim(base_url(), '/') . '/uploads/soal/' . $fileName;

                log_message('info', 'File uploaded successfully: ' . $imageUrl);
                log_message('info', 'Base URL: ' . base_url());

                echo json_encode(['location' => $imageUrl]);
            } else {
                log_message('error', 'Failed to move uploaded file to: ' . $targetFile);
                echo json_encode(['error' => 'Gagal memindahkan file ke folder upload']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Upload exception: ' . $e->getMessage());
            echo json_encode(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    public function uploadImageBase64()
    {
        // Set JSON header
        $this->response->setHeader('Content-Type', 'application/json');
        
        try {
            $imageData = $this->request->getPost('image_data');
            
            if (!$imageData) {
                return $this->response->setJSON([
                    'error' => 'Data gambar tidak ditemukan'
                ]);
            }
            
            // Parse base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = $matches[1];
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);
                
                if ($imageData === false) {
                    return $this->response->setJSON([
                        'error' => 'Data base64 tidak valid'
                    ]);
                }
                
                // Validasi tipe gambar
                $allowedTypes = ['jpeg', 'jpg', 'png', 'gif'];
                if (!in_array(strtolower($imageType), $allowedTypes)) {
                    return $this->response->setJSON([
                        'error' => 'Tipe gambar tidak diizinkan'
                    ]);
                }
                
                // Generate nama file unik
                $fileName = uniqid() . '_' . time() . '.' . $imageType;
                $uploadPath = FCPATH . 'uploads/soal/';
                
                // Buat folder jika belum ada
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Simpan file
                if (file_put_contents($uploadPath . $fileName, $imageData)) {
                    $imageUrl = rtrim(base_url(), '/') . '/uploads/soal/' . $fileName;

                    return $this->response->setJSON([
                        'location' => $imageUrl
                    ]);
                } else {
                    return $this->response->setJSON([
                        'error' => 'Gagal menyimpan file'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'error' => 'Format data gambar tidak valid'
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
