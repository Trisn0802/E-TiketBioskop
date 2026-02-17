<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/env.php';
loadEnvFile(dirname(__DIR__) . '/.env');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$startAt = date('Y-m-d H:i:s');

$config = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASS'),
    'name' => getenv('DB_NAME'),
    'port' => (int) (getenv('DB_PORT')),
];

try {
    $conn = new mysqli(
        $config['host'],
        $config['user'],
        $config['pass'],
        $config['name'],
        $config['port']
    );
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    fwrite(STDERR, '[ERROR] Koneksi database gagal: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$steps = [];

function runSql(mysqli $conn, string $sql, string $okMessage): void
{
    global $steps;
    $conn->query($sql);
    $steps[] = '[OK] ' . $okMessage;
}

function tableExists(mysqli $conn, string $table): bool
{
    $stmt = $conn->prepare(
        'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1'
    );
    $stmt->bind_param('s', $table);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_row();
}

function columnExists(mysqli $conn, string $table, string $column): bool
{
    $stmt = $conn->prepare(
        'SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1'
    );
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_row();
}

function indexExists(mysqli $conn, string $table, string $indexName): bool
{
    $stmt = $conn->prepare(
        'SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1'
    );
    $stmt->bind_param('ss', $table, $indexName);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_row();
}

function primaryKeyExists(mysqli $conn, string $table): bool
{
    $stmt = $conn->prepare(
        "SELECT 1 FROM information_schema.table_constraints WHERE table_schema = DATABASE() AND table_name = ? AND constraint_type = 'PRIMARY KEY' LIMIT 1"
    );
    $stmt->bind_param('s', $table);
    $stmt->execute();
    return (bool) $stmt->get_result()->fetch_row();
}

function addStep(string $msg): void
{
    global $steps;
    $steps[] = '[SKIP] ' . $msg;
}

try {
    runSql(
        $conn,
        "CREATE TABLE IF NOT EXISTS pengumuman (
            pengumuman_id TINYINT NOT NULL DEFAULT 1,
            judul VARCHAR(255) NOT NULL,
            isi LONGTEXT NOT NULL,
            status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
            dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            diupdate_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (pengumuman_id),
            CONSTRAINT chk_single_pengumuman CHECK (pengumuman_id = 1)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        'Tabel pengumuman siap'
    );

    if (!columnExists($conn, 'pengumuman', 'diupdate_pada')) {
        runSql(
            $conn,
            'ALTER TABLE pengumuman ADD COLUMN diupdate_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'Kolom pengumuman.diupdate_pada ditambahkan'
        );
    } else {
        addStep('Kolom pengumuman.diupdate_pada sudah ada');
    }

    runSql(
        $conn,
        "INSERT INTO pengumuman (pengumuman_id, judul, isi, status)
         SELECT 1, 'Pengumuman', '<p>Belum ada pengumuman.</p>', 'nonaktif'
         WHERE NOT EXISTS (SELECT 1 FROM pengumuman WHERE pengumuman_id = 1)",
        'Seed data pengumuman default dipastikan'
    );

    if (!tableExists($conn, 'pelanggan')) {
        throw new RuntimeException('Tabel pelanggan tidak ditemukan.');
    }

    if (!columnExists($conn, 'pelanggan', 'foto')) {
        runSql(
            $conn,
            "ALTER TABLE pelanggan ADD COLUMN foto VARCHAR(50) NOT NULL DEFAULT ''",
            'Kolom pelanggan.foto ditambahkan'
        );
    } else {
        addStep('Kolom pelanggan.foto sudah ada');
    }

    if (!columnExists($conn, 'pelanggan', 'role')) {
        runSql(
            $conn,
            "ALTER TABLE pelanggan ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'",
            'Kolom pelanggan.role ditambahkan'
        );
    } else {
        addStep('Kolom pelanggan.role sudah ada');
    }

    if (columnExists($conn, 'pelanggan', 'password')) {
        runSql(
            $conn,
            'ALTER TABLE pelanggan MODIFY password VARCHAR(255) NOT NULL',
            'Kolom pelanggan.password dipastikan VARCHAR(255)'
        );
    } else {
        throw new RuntimeException('Kolom pelanggan.password tidak ditemukan.');
    }

    runSql(
        $conn,
        "UPDATE pelanggan
         SET foto = 'admin_profil.jpg'
         WHERE role = 'admin' AND (foto IS NULL OR foto = '')",
        'Foto default admin dipastikan'
    );

    if (!indexExists($conn, 'pelanggan', 'idx_pelanggan_role')) {
        runSql(
            $conn,
            'CREATE INDEX idx_pelanggan_role ON pelanggan(role)',
            'Index pelanggan.role ditambahkan'
        );
    } else {
        addStep('Index idx_pelanggan_role sudah ada');
    }

    if (tableExists($conn, 'transaksi') && !indexExists($conn, 'transaksi', 'idx_transaksi_pelanggan_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_transaksi_pelanggan_id ON transaksi(pelanggan_id)',
            'Index transaksi.pelanggan_id ditambahkan'
        );
    } else {
        addStep('Index transaksi.pelanggan_id sudah ada / tabel transaksi belum ada');
    }

    if (tableExists($conn, 'transaksi') && !indexExists($conn, 'transaksi', 'idx_transaksi_jadwal_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_transaksi_jadwal_id ON transaksi(jadwal_id)',
            'Index transaksi.jadwal_id ditambahkan'
        );
    } else {
        addStep('Index transaksi.jadwal_id sudah ada / tabel transaksi belum ada');
    }

    if (tableExists($conn, 'jadwal') && !indexExists($conn, 'jadwal', 'idx_jadwal_film_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_jadwal_film_id ON jadwal(film_id)',
            'Index jadwal.film_id ditambahkan'
        );
    } else {
        addStep('Index jadwal.film_id sudah ada / tabel jadwal belum ada');
    }

    if (tableExists($conn, 'kursi_jadwal') && !indexExists($conn, 'kursi_jadwal', 'idx_kursi_jadwal_kursi_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_kursi_jadwal_kursi_id ON kursi_jadwal(kursi_id)',
            'Index kursi_jadwal.kursi_id ditambahkan'
        );
    } else {
        addStep('Index kursi_jadwal.kursi_id sudah ada / tabel kursi_jadwal belum ada');
    }

    if (tableExists($conn, 'kursi_jadwal') && !indexExists($conn, 'kursi_jadwal', 'idx_kursi_jadwal_jadwal_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_kursi_jadwal_jadwal_id ON kursi_jadwal(jadwal_id)',
            'Index kursi_jadwal.jadwal_id ditambahkan'
        );
    } else {
        addStep('Index kursi_jadwal.jadwal_id sudah ada / tabel kursi_jadwal belum ada');
    }

    if (tableExists($conn, 'detail_transaksi') && !indexExists($conn, 'detail_transaksi', 'idx_detail_transaksi_transaksi_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_detail_transaksi_transaksi_id ON detail_transaksi(transaksi_id)',
            'Index detail_transaksi.transaksi_id ditambahkan'
        );
    } else {
        addStep('Index detail_transaksi.transaksi_id sudah ada / tabel detail_transaksi belum ada');
    }

    if (tableExists($conn, 'detail_transaksi') && !indexExists($conn, 'detail_transaksi', 'idx_detail_transaksi_kursi_id')) {
        runSql(
            $conn,
            'CREATE INDEX idx_detail_transaksi_kursi_id ON detail_transaksi(kursi_id)',
            'Index detail_transaksi.kursi_id ditambahkan'
        );
    } else {
        addStep('Index detail_transaksi.kursi_id sudah ada / tabel detail_transaksi belum ada');
    }

    if (tableExists($conn, 'pengumuman') && !primaryKeyExists($conn, 'pengumuman')) {
        runSql(
            $conn,
            'ALTER TABLE pengumuman ADD PRIMARY KEY (pengumuman_id)',
            'Primary key pengumuman dipastikan'
        );
    } else {
        addStep('Primary key pengumuman sudah ada');
    }

    echo '[INFO] Migrasi dimulai: ' . $startAt . PHP_EOL;
    echo '[INFO] Migrasi selesai: ' . date('Y-m-d H:i:s') . PHP_EOL;
    foreach ($steps as $step) {
        echo $step . PHP_EOL;
    }

    $conn->close();
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, '[ERROR] Migrasi gagal: ' . $e->getMessage() . PHP_EOL);
    $conn->close();
    exit(1);
}
