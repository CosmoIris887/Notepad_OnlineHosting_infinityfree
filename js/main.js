/**
 * Antigravity Notes - Script Interaksi Dashboard
 * Mengelola modal CRUD, live-filtering pencarian catatan, penanganan dismiss alert, dan validasi form modal.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. PENGELOLAAN ALERT (Dismissible Alert)
    // ==========================================
    const alertDismissBtns = document.querySelectorAll('.alert-close-btn');
    alertDismissBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const alertElement = this.closest('.alert');
            if (alertElement) {
                alertElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                alertElement.style.opacity = '0';
                alertElement.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alertElement.remove();
                }, 300);
            }
        });
    });

    // ==========================================
    // 2. MODAL TAMBAH CATATAN
    // ==========================================
    const addNoteModal = document.getElementById('addNoteModal');
    const openAddModalBtn = document.getElementById('openAddModalBtn');
    const emptyStateAddBtn = document.getElementById('emptyStateAddBtn');
    const closeAddModalBtn = document.getElementById('closeAddModalBtn');
    const cancelAddNoteBtn = document.getElementById('cancelAddNoteBtn');
    const addNoteForm = document.getElementById('addNoteForm');

    function openAddModal() {
        if (addNoteModal) {
            addNoteModal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Kunci scroll latar belakang
            document.getElementById('addTitle').focus();
        }
    }

    function closeAddModal() {
        if (addNoteModal) {
            addNoteModal.classList.remove('show');
            document.body.style.overflow = '';
            addNoteForm.reset();
            
            // Reset state validasi
            const addTitle = document.getElementById('addTitle');
            addTitle.classList.remove('is-invalid');
            const errorElement = document.getElementById('addTitleError');
            if (errorElement) {
                errorElement.style.display = 'none';
                errorElement.textContent = '';
            }
        }
    }

    if (openAddModalBtn) openAddModalBtn.addEventListener('click', openAddModal);
    if (emptyStateAddBtn) emptyStateAddBtn.addEventListener('click', openAddModal);
    if (closeAddModalBtn) closeAddModalBtn.addEventListener('click', closeAddModal);
    if (cancelAddNoteBtn) cancelAddNoteBtn.addEventListener('click', closeAddModal);

    // ==========================================
    // 3. MODAL EDIT & VIEW CATATAN
    // ==========================================
    const editNoteModal = document.getElementById('editNoteModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const cancelEditNoteBtn = document.getElementById('cancelEditNoteBtn');
    const editNoteForm = document.getElementById('editNoteForm');
    
    const editNoteIdInput = document.getElementById('editNoteId');
    const editTitleInput = document.getElementById('editTitle');
    const editContentInput = document.getElementById('editContent');
    const editPinnedInput = document.getElementById('editPinned');

    function closeEditModal() {
        if (editNoteModal) {
            editNoteModal.classList.remove('show');
            document.body.style.overflow = '';
            editNoteForm.reset();
            
            editTitleInput.classList.remove('is-invalid');
            const errorElement = document.getElementById('editTitleError');
            if (errorElement) {
                errorElement.style.display = 'none';
                errorElement.textContent = '';
            }
        }
    }

    if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
    if (cancelEditNoteBtn) cancelEditNoteBtn.addEventListener('click', closeEditModal);

    // Event listener untuk klik "Edit" di kartu catatan
    const editButtons = document.querySelectorAll('.edit-note-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah double trigger dari klik kartu
            const noteCard = this.closest('.note-card');
            openEditModalWithData(noteCard);
        });
    });

    // Event listener agar klik isi body kartu juga membuka edit modal (View/Edit)
    const cardBodies = document.querySelectorAll('.note-card-body');
    cardBodies.forEach(body => {
        body.addEventListener('click', function() {
            const noteCard = this.closest('.note-card');
            openEditModalWithData(noteCard);
        });
    });

    // Mengisi form edit dengan data dari atribut kartu
    function openEditModalWithData(noteCard) {
        if (!noteCard || !editNoteModal) return;
        
        const noteId = noteCard.getAttribute('data-id');
        const noteTitle = noteCard.getAttribute('data-title');
        const noteContent = noteCard.getAttribute('data-content');
        const noteColor = noteCard.getAttribute('data-color');
        const notePinned = noteCard.getAttribute('data-pinned');

        // Isi form modal
        editNoteIdInput.value = noteId;
        editTitleInput.value = noteTitle;
        editContentInput.value = noteContent;
        editPinnedInput.checked = (notePinned === '1');

        // Pilih warna radio button yang sesuai
        const colorRadio = editNoteModal.querySelector(`input[name="color"][value="${noteColor}"]`);
        if (colorRadio) {
            colorRadio.checked = true;
        }

        // Tampilkan modal
        editNoteModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        editTitleInput.focus();
    }

    // ==========================================
    // 4. MODAL KONFIRMASI HAPUS (KUSTOM)
    // ==========================================
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const closeDeleteModalBtn = document.getElementById('closeDeleteModalBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteLink = document.getElementById('confirmDeleteLink');

    function closeDeleteModal() {
        if (deleteConfirmModal) {
            deleteConfirmModal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if (closeDeleteModalBtn) closeDeleteModalBtn.addEventListener('click', closeDeleteModal);
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);

    const deleteButtons = document.querySelectorAll('.delete-note-btn');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah klik menembus ke kartu
            const noteId = this.getAttribute('data-id');
            
            if (deleteConfirmModal && confirmDeleteLink) {
                // Set link hapus ke action controller PHP dengan parameter ID
                confirmDeleteLink.href = `actions/note_action.php?action=delete&id=${noteId}`;
                deleteConfirmModal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // ==========================================
    // 5. VALIDASI CLIENT-SIDE FORM MODAL
    // ==========================================
    if (addNoteForm) {
        addNoteForm.addEventListener('submit', function(e) {
            const titleInput = document.getElementById('addTitle');
            const errorElement = document.getElementById('addTitleError');
            
            if (titleInput.value.trim() === '') {
                e.preventDefault();
                titleInput.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Judul catatan tidak boleh kosong!';
                    errorElement.style.display = 'block';
                }
            }
        });
    }

    if (editNoteForm) {
        editNoteForm.addEventListener('submit', function(e) {
            const titleInput = document.getElementById('editTitle');
            const errorElement = document.getElementById('editTitleError');
            
            if (titleInput.value.trim() === '') {
                e.preventDefault();
                titleInput.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Judul catatan tidak boleh kosong!';
                    errorElement.style.display = 'block';
                }
            }
        });
    }

    // ==========================================
    // 6. LIVE SEARCH (PENCARIAN INSTAN)
    // ==========================================
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const notesGrid = document.getElementById('notesGrid');
    const emptyState = document.getElementById('emptyState');
    const noteCards = document.querySelectorAll('.note-card');
    
    // Buat elemen visual "Tidak ada hasil pencarian" secara dinamis
    let noSearchMatch = document.getElementById('noSearchMatch');
    if (!noSearchMatch && notesGrid) {
        noSearchMatch = document.createElement('div');
        noSearchMatch.id = 'noSearchMatch';
        noSearchMatch.className = 'empty-state-container glass-effect';
        noSearchMatch.style.display = 'none';
        noSearchMatch.style.gridColumn = '1 / -1'; // Membentang sepanjang grid kolom
        noSearchMatch.innerHTML = `
            <div class="empty-icon"><i class="fa-solid fa-magnifying-glass-minus text-muted"></i></div>
            <h3>Pencarian Tidak Ditemukan</h3>
            <p>Tidak ada catatan yang cocok dengan kata kunci pencarian Anda. Silakan coba kata kunci lain.</p>
        `;
        notesGrid.appendChild(noSearchMatch);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            // Tampilkan/sembunyikan tombol clear search
            if (query.length > 0) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
            
            let visibleCount = 0;
            
            noteCards.forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                const content = card.getAttribute('data-content').toLowerCase();
                
                if (title.includes(query) || content.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Penanganan visual jika tidak ada catatan yang cocok
            if (visibleCount === 0 && query.length > 0) {
                if (noSearchMatch) noSearchMatch.style.display = 'flex';
            } else {
                if (noSearchMatch) noSearchMatch.style.display = 'none';
            }
        });
        
        // Tombol Hapus Pencarian
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }
    }

    // ==========================================
    // 7. MENUTUP MODAL DENGAN KLIK DI LUAR MODAL
    // ==========================================
    window.addEventListener('click', function(e) {
        if (e.target === addNoteModal) closeAddModal();
        if (e.target === editNoteModal) closeEditModal();
        if (e.target === deleteConfirmModal) closeDeleteModal();
    });
    
    // Menutup modal dengan tombol ESC
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
});
