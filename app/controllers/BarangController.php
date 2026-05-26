<?php
require_once __DIR__ . '/../models/BarangModel.php';

class BarangController {
    private $model;

    public function __construct() {
        $this->model = new BarangModel();
    }

    // 1. Menampilkan Daftar Barang (Index)
    public function index() {
        $limit = 5;
        $page_now = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
        $start = ($page_now - 1) * $limit;
        $search = $_GET['search'] ?? '';

        // Panggil data dari Model (Tanpa ada SQL Query di sini)
        $total_data = $this->model->getTotalData($search);
        $barang_list = $this->model->getData($start, $limit, $search);
        $total_page = ceil($total_data / $limit);

        // Lempar data ke komponen View
        require_once __DIR__ . '/../views/barang/index.php';
    }

    // 2. Form & Proses Tambah Barang
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $kode_barang = trim($_POST['kode_barang']);
            $nama_barang = trim($_POST['nama_barang']);
            $jumlah = $_POST['jumlah'];
            $kategori = $_POST['kategori'];
            $harga = $_POST['harga'];
            $keterangan = $_POST['keterangan'];
            
            $errors = [];

            // Validasi Sisi Server
            if (empty($kode_barang)) $errors[] = "Kode barang tidak boleh kosong.";
            if (empty($nama_barang)) $errors[] = "Nama barang tidak boleh kosong.";
            if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = "Jumlah harus berupa angka valid.";
            if (empty($kategori)) $errors[] = "Kategori tidak boleh kosong.";
            if (!is_numeric($harga) || $harga < 0) $errors[] = "Harga harus berupa angka valid.";

            // Cek duplikasi kode barang via Model
            if ($this->model->checkKodeBarang($kode_barang)) {
                $errors[] = "Kode barang sudah terdaftar di sistem!";
            }

            // Logika Unggah Gambar
            $nama_file_unik = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['gambar']['tmp_name'];
                $file_name = $_FILES['gambar']['name'];
                $file_size = $_FILES['gambar']['size'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                $allowed_ext = ['jpg', 'jpeg', 'png'];
                if (!in_array($file_ext, $allowed_ext)) $errors[] = "Format file harus JPG, JPEG, atau PNG.";
                if ($file_size > 2000000) $errors[] = "Ukuran file maksimal 2MB.";

                if (empty($errors)) {
                    $nama_file_unik = uniqid() . '_' . $file_name;
                    $target_dir = __DIR__ . "/../../public/uploads/";
                    if (!move_uploaded_file($file_tmp, $target_dir . $nama_file_unik)) {
                        $errors[] = "Gagal mengunggah gambar.";
                    }
                }
            }

            if (empty($errors)) {
                $data = [
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'jumlah' => $jumlah,
                    'kategori' => $kategori,
                    'harga' => $harga,
                    'keterangan' => $keterangan,
                    'gambar' => $nama_file_unik
                ];
                
                if ($this->model->save($data)) {
                    $_SESSION['pesan'] = 'Barang Berhasil Ditambahkan!';
                    $_SESSION['tipe'] = 'success';
                    header('Location: index.php?page=data_barang');
                    exit();
                }
            } else {
                $_SESSION['pesan'] = implode("<br>", $errors);
                $_SESSION['tipe'] = "error";
            }
        }
        require_once __DIR__ . '/../views/barang/create.php';
    }

    // 3. Form & Proses Edit Barang
    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $barang = $this->model->getById($id);

        if (!$barang) {
            $_SESSION['pesan'] = "Barang Tidak Ditemukan!";
            $_SESSION['tipe'] = "error";
            header("Location: index.php?page=data_barang");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_barang = trim($_POST['nama_barang']);
            $jumlah      = $_POST['jumlah'];
            $kategori    = $_POST['kategori'];
            $harga       = $_POST['harga'];
            $keterangan  = $_POST['keterangan'];
            
            $errors = [];

            if (empty($nama_barang)) $errors[] = "Nama barang tidak boleh kosong.";
            if (!is_numeric($jumlah) || !is_numeric($harga)) $errors[] = "Jumlah dan harga harus angka.";

            $nama_file_unik = $barang['gambar'];

            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $file_tmp  = $_FILES['gambar']['tmp_name'];
                $file_name = $_FILES['gambar']['name'];
                $file_size = $_FILES['gambar']['size'];
                $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                $allowed_ext = ['jpg', 'jpeg', 'png'];
                if (!in_array($file_ext, $allowed_ext)) $errors[] = "Format file salah.";
                if ($file_size > 2000000) $errors[] = "Ukuran file maks 2MB.";

                if (empty($errors)) {
                    $nama_file_unik = uniqid() . '_' . $file_name;
                    $target_dir = __DIR__ . "/../../public/uploads/";

                    if (move_uploaded_file($file_tmp, $target_dir . $nama_file_unik)) {
                        if (!empty($barang['gambar']) && file_exists($target_dir . $barang['gambar'])) {
                            unlink($target_dir . $barang['gambar']);
                        }
                    } else {
                        $errors[] = "Gagal mengunggah gambar baru.";
                    }
                }
            }

            if (empty($errors)) {
                $data = [
                    'nama_barang' => $nama_barang,
                    'jumlah' => $jumlah,
                    'kategori' => $kategori,
                    'harga' => $harga,
                    'keterangan' => $keterangan,
                    'gambar' => $nama_file_unik
                ];

                if ($this->model->update($id, $data)) {
                    $_SESSION['pesan'] = 'Barang Berhasil diperbarui!';
                    $_SESSION['tipe'] = 'success';
                    header('Location: index.php?page=data_barang');
                    exit();
                }
            } else {
                $_SESSION['pesan'] = implode("<br>", $errors);
                $_SESSION['tipe'] = "error";
            }
        }
        require_once __DIR__ . '/../views/barang/edit.php';
    }

    // 4. Proses Hapus Barang beserta File Gambar fisik
    public function delete() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $barang = $this->model->getById($id);

        if ($barang) {
            $this->model->delete($id);
            if (!empty($barang['gambar'])) {
                $file_path = __DIR__ . "/../../public/uploads/" . $barang['gambar'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $_SESSION['pesan'] = 'Data dan gambar berhasil dihapus!';
            $_SESSION['tipe'] = 'success';
        } else {
            $_SESSION['pesan'] = 'Data tidak ditemukan!';
            $_SESSION['tipe'] = 'error';
        }
        header('Location: index.php?page=data_barang');
        exit();
    }
}