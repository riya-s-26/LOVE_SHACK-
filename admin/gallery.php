<?php
/**
 * THE LOVE SHACK — Admin: Photo Gallery Management
 */

declare(strict_types=1);

$adminPage = 'gallery';
$adminTitle = 'Gallery Management';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$notice = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$pdo) {
        $error = "Database connection unavailable.";
    } else {
        if ($_POST['action'] === 'add_image') {
            $title = trim((string)($_POST['title'] ?? ''));
            $caption = trim((string)($_POST['caption'] ?? ''));
            $category = trim((string)($_POST['category'] ?? 'Villa & Pool'));
            $imageUrl = trim((string)($_POST['image_url'] ?? ''));
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($title) || empty($imageUrl)) {
                $error = "Title and image path are required.";
            } else {
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO gallery_images (property_id, title, caption, category, image_url, display_order, is_featured)
                        VALUES (1, ?, ?, ?, ?, 99, ?)
                    ");
                    $stmt->execute([$title, $caption, $category, $imageUrl, $isFeatured]);
                    $notice = "Gallery image '{$title}' added!";
                } catch (Exception $e) {
                    $error = "Failed to add image: " . $e->getMessage();
                }
            }
        } elseif ($_POST['action'] === 'delete_image') {
            $id = (int)($_POST['image_id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
                $stmt->execute([$id]);
                $notice = "Image deleted from gallery.";
            } catch (Exception $e) {
                $error = "Failed to delete: " . $e->getMessage();
            }
        }
    }
}

$images = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM gallery_images WHERE property_id = 1 ORDER BY display_order ASC");
        $images = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching gallery images: " . $e->getMessage());
    }
} else {
    $images = get_gallery_images();
}
?>

<div class="admin-header-row">
  <div>
    <h1>Photo Gallery Management</h1>
    <p>Organize photographs, update editorial captions, and select featured preview imagery.</p>
  </div>
</div>

<?php if (!empty($notice)): ?>
  <div style="background: #ecfdf5; border-left: 4px solid var(--color-secondary); color: #065f46; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($notice) ?>
  </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 18px; border-radius: var(--radius-sm); margin-bottom: 24px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<!-- Add Image Form -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Add Photograph to Gallery</h2>
  </div>

  <form method="POST" action="gallery.php">
    <input type="hidden" name="action" value="add_image">

    <div class="admin-grid-3">
      <div class="admin-form-group">
        <label for="title">Photo Title *</label>
        <input type="text" id="title" name="title" placeholder="e.g. Master Bedroom Sea View" required>
      </div>

      <div class="admin-form-group">
        <label for="category">Category *</label>
        <select id="category" name="category" required>
          <option value="Exterior">Exterior & Veranda</option>
          <option value="Pool & Garden">Pool & Garden</option>
          <option value="Beach & Ocean">Beach & Ocean</option>
          <option value="Suites & Rooms">Suites & Rooms</option>
          <option value="Kitchen & Living">Kitchen & Living</option>
        </select>
      </div>

      <div class="admin-form-group">
        <label for="image_url">Image File Path *</label>
        <input type="text" id="image_url" name="image_url" placeholder="assets/images/temporary-hero.jpg" required>
      </div>
    </div>

    <div class="admin-form-group">
      <label for="caption">Editorial Caption</label>
      <input type="text" id="caption" name="caption" placeholder="Descriptive caption for the photo lightbox view...">
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 10px;">
      <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
        <input type="checkbox" name="is_featured" value="1" checked style="width: auto;">
        Show on homepage preview gallery
      </label>

      <button type="submit" class="btn btn-teal" style="padding: 10px 24px; font-size: 0.78rem;">
        Add Photograph
      </button>
    </div>
  </form>
</div>

<!-- Gallery Grid Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Configured Photographs (<?= count($images) ?>)</h2>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Preview</th>
          <th>Title & Category</th>
          <th>Caption</th>
          <th>Featured</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($images as $img): ?>
          <tr>
            <td style="width: 100px;">
              <img src="../<?= htmlspecialchars($img['image_url']) ?>" alt="Thumbnail" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
            </td>
            <td>
              <strong><?= htmlspecialchars($img['title']) ?></strong><br>
              <small style="color: var(--color-secondary);"><?= htmlspecialchars($img['category']) ?></small>
            </td>
            <td style="color: var(--color-text-muted); font-size: 0.85rem;">
              <?= htmlspecialchars($img['caption']) ?>
            </td>
            <td>
              <?php if (!empty($img['is_featured'])): ?>
                <span class="badge badge-confirmed">HOMEPAGE</span>
              <?php else: ?>
                <span class="badge badge-read">STANDARD</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="gallery.php" onsubmit="return confirm('Delete this photograph from gallery?');" style="display:inline;">
                <input type="hidden" name="action" value="delete_image">
                <input type="hidden" name="image_id" value="<?= $img['id'] ?? 0 ?>">
                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.7rem; border-color: #fecaca; color: #b91c1c;">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
