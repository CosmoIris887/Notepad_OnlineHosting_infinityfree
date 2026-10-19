<?php
/**
 * Halaman Dashboard Utama Pengguna
 * PHP Native Notes App
 */
$page_title = "Dashboard Catatan";
require_once __DIR__ . '/includes/header.php';

// Memeriksa apakah file koneksi tersedia
require_once __DIR__ . '/config/database.php';

$user_id = $_SESSION['user_id'];
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

// Hapus alert setelah diambil
unset($_SESSION['error']);
unset($_SESSION['success']);

// Query untuk mengambil semua catatan user, diurutkan dari yang dipin dahulu, kemudian tanggal update terbaru
try {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY is_pinned DESC, updated_at DESC");
    $stmt->execute([$user_id]);
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    $notes = [];
    $error = "Gagal mengambil data catatan dari database: " . $e->getMessage();
}

// Skema Warna Premium untuk Pilihan di Form (Hex Code -> Label CSS)
$color_palette = [
    '#6366f1' => 'indigo',
    '#3b82f6' => 'blue',
    '#10b981' => 'emerald',
    '#f59e0b' => 'amber',
    '#ec4899' => 'pink',
    '#8b5cf6' => 'violet'
];
?>

<div class="dashboard-container">
    
    <!-- Bagian Toolbar: Judul, Tombol Tambah, & Search Bar -->
    <div class="dashboard-toolbar glass-effect">
        <div class="toolbar-left">
            <h2>Koleksi Catatan</h2>
            <p>Kelola semua tugas, ide, dan rencana Anda di satu tempat.</p>
        </div>
        <div class="toolbar-right">
            <!-- Search Input -->
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari judul atau isi catatan..." autocomplete="off">
                <button id="clearSearchBtn" class="clear-search-btn" style="display: none;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Button Trigger Modal Tambah -->
            <button class="btn btn-primary" id="openAddModalBtn">
                <i class="fa-solid fa-plus"></i> Catatan Baru
            </button>
        </div>
    </div>

    <!-- Menampilkan Alert Info -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible">
            <span><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?></span>
            <button class="alert-close-btn">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible">
            <span><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?></span>
            <button class="alert-close-btn">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Empty State (Jika belum ada catatan) -->
    <div class="empty-state-container glass-effect" id="emptyState" style="<?php echo count($notes) > 0 ? 'display: none;' : ''; ?>">
        <div class="empty-icon"><i class="fa-regular fa-note-sticky"></i></div>
        <h3>Belum Ada Catatan</h3>
        <p>Anda belum membuat catatan apa pun. Klik tombol di bawah untuk mulai menuangkan ide-ide cemerlang Anda!</p>
        <button class="btn btn-primary" id="emptyStateAddBtn">
            <i class="fa-solid fa-plus"></i> Buat Catatan Pertama
        </button>
    </div>

    <!-- Grid Catatan -->
    <div class="notes-grid" id="notesGrid" style="<?php echo count($notes) === 0 ? 'display: none;' : ''; ?>">
        <?php foreach ($notes as $note): 
            $note_color_class = $color_palette[$note['color']] ?? 'indigo';
            $formatted_date = date('d M Y, H:i', strtotime($note['updated_at']));
        ?>
            <!-- Note Card -->
            <div class="note-card glass-effect note-color-<?php echo $note_color_class; ?>" 
                 data-id="<?php echo $note['id']; ?>"
                 data-title="<?php echo htmlspecialchars($note['title']); ?>"
                 data-content="<?php echo htmlspecialchars($note['content']); ?>"
                 data-color="<?php echo $note['color']; ?>"
                 data-pinned="<?php echo $note['is_pinned']; ?>">
                
                <!-- Card Header (Pin Status & Color Indicator) -->
                <div class="note-card-header">
                    <span class="note-color-dot" style="background-color: <?php echo $note['color']; ?>;"></span>
                    <a href="actions/note_action.php?action=toggle_pin&id=<?php echo $note['id']; ?>" 
                       class="pin-btn <?php echo $note['is_pinned'] ? 'pinned' : ''; ?>" 
                       title="<?php echo $note['is_pinned'] ? 'Lepas Sematan' : 'Sematkan Catatan'; ?>">
                        <i class="<?php echo $note['is_pinned'] ? 'fa-solid' : 'fa-regular'; ?> fa-thumbtack"></i>
                    </a>
                </div>
                
                <!-- Card Body (Title & Truncated Content) -->
                <div class="note-card-body">
                    <h3 class="note-card-title"><?php echo htmlspecialchars($note['title']); ?></h3>
                    <div class="note-card-content"><?php echo nl2br(htmlspecialchars($note['content'])); ?></div>
                </div>
                
                <!-- Card Footer (Date & Actions) -->
                <div class="note-card-footer">
                    <span class="note-card-date"><i class="fa-regular fa-clock"></i> <?php echo $formatted_date; ?></span>
                    <div class="note-card-actions">
                        <button class="action-icon-btn edit-note-btn" title="Edit Catatan">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="action-icon-btn delete-note-btn" title="Hapus Catatan" data-id="<?php echo $note['id']; ?>">
                            <i class="fa-solid fa-trash-can text-danger"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: TAMBAH CATATAN NEW -->
<!-- ========================================== -->
<div class="modal" id="addNoteModal">
    <div class="modal-content glass-effect animate-zoom">
        <div class="modal-header">
            <h3><i class="fa-solid fa-plus-circle"></i> Buat Catatan Baru</h3>
            <button class="modal-close-btn" id="closeAddModalBtn">&times;</button>
        </div>
        <form action="actions/note_action.php" method="POST" id="addNoteForm">
            <input type="hidden" name="action" value="create">
            
            <div class="modal-body">
                <!-- Title Input -->
                <div class="form-group">
                    <label for="addTitle">Judul Catatan</label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Tulis judul yang menarik..." required>
                    <div class="invalid-feedback" id="addTitleError"></div>
                </div>
                
                <!-- Content Input -->
                <div class="form-group">
                    <label for="addContent">Isi Catatan</label>
                    <textarea name="content" id="addContent" class="form-control" rows="6" placeholder="Tuliskan pemikiran atau detail catatan Anda disini..."></textarea>
                </div>
                
                <!-- Custom Color Palette Selection -->
                <div class="form-group">
                    <label>Pilih Warna Kartu</label>
                    <div class="color-picker-grid">
                        <?php 
                        $first_color = true;
                        foreach ($color_palette as $hex => $name): 
                        ?>
                            <label class="color-option-label" style="background-color: <?php echo $hex; ?>;">
                                <input type="radio" name="color" value="<?php echo $hex; ?>" <?php echo $first_color ? 'checked' : ''; ?>>
                                <span class="color-checkmark"><i class="fa-solid fa-check"></i></span>
                            </label>
                        <?php 
                        $first_color = false;
                        endforeach; 
                        ?>
                    </div>
                </div>
                
                <!-- Pin Checkbox -->
                <div class="form-group checkbox-group">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="is_pinned" value="1">
                        <span class="checkmark"><i class="fa-solid fa-check"></i></span>
                        Sematkan catatan ini di bagian atas dashboard
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelAddNoteBtn">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: EDIT / VIEW CATATAN -->
<!-- ========================================== -->
<div class="modal" id="editNoteModal">
    <div class="modal-content glass-effect animate-zoom">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Edit Catatan</h3>
            <button class="modal-close-btn" id="closeEditModalBtn">&times;</button>
        </div>
        <form action="actions/note_action.php" method="POST" id="editNoteForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="editNoteId">
            
            <div class="modal-body">
                <!-- Title Input -->
                <div class="form-group">
                    <label for="editTitle">Judul Catatan</label>
                    <input type="text" name="title" id="editTitle" class="form-control" placeholder="Tulis judul catatan..." required>
                    <div class="invalid-feedback" id="editTitleError"></div>
                </div>
                
                <!-- Content Input -->
                <div class="form-group">
                    <label for="editContent">Isi Catatan</label>
                    <textarea name="content" id="editContent" class="form-control" rows="6" placeholder="Tulis detail catatan..."></textarea>
                </div>
                
                <!-- Custom Color Palette Selection -->
                <div class="form-group">
                    <label>Pilih Warna Kartu</label>
                    <div class="color-picker-grid">
                        <?php foreach ($color_palette as $hex => $name): ?>
                            <label class="color-option-label" style="background-color: <?php echo $hex; ?>;">
                                <input type="radio" name="color" id="colorEdit_<?php echo $name; ?>" value="<?php echo $hex; ?>">
                                <span class="color-checkmark"><i class="fa-solid fa-check"></i></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Pin Checkbox -->
                <div class="form-group checkbox-group">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="is_pinned" id="editPinned" value="1">
                        <span class="checkmark"><i class="fa-solid fa-check"></i></span>
                        Sematkan catatan ini di bagian atas dashboard
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelEditNoteBtn">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: KONFIRMASI HAPUS (KUSTOM) -->
<!-- ========================================== -->
<div class="modal" id="deleteConfirmModal">
    <div class="modal-content glass-effect animate-zoom modal-confirm">
        <div class="modal-header">
            <h3>Konfirmasi Hapus</h3>
            <button class="modal-close-btn" id="closeDeleteModalBtn">&times;</button>
        </div>
        <div class="modal-body text-center">
            <div class="delete-warning-icon"><i class="fa-solid fa-circle-exclamation text-danger"></i></div>
            <p>Apakah Anda yakin ingin menghapus catatan ini secara permanen?</p>
            <p class="text-muted text-sm">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Batal</button>
            <a href="#" class="btn btn-danger" id="confirmDeleteLink">Ya, Hapus</a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
