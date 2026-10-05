<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../Controllers/ProductController.php';

require_admin();

$controller = new ProductController();

$editMode = false;
$category = null;

if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($editId && $editId > 0) {
        $category = $controller->getCategoryById($editId);

        if ($category) {
            $editMode = true;
        } else {
            $_SESSION['error'] = 'Category not found.';
        }
    }
}

$categories = $controller->getAllCategories();

include __DIR__ . '/../layout/header.php';
?>

<div class="admin-container">
    <section class="admin-card">
        <h2><?php echo $editMode ? 'Edit Category' : 'Add Category'; ?></h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form
            action="<?php echo $editMode
                ? '../../actions/update_category_action.php'
                : '../../actions/add_category_action.php'; ?>"
            method="POST"
        >
            <?php if ($editMode): ?>
                <input
                    type="hidden"
                    name="cat_id"
                    value="<?php echo (int) $category['cat_id']; ?>"
                >
            <?php endif; ?>

            <label for="cat_name">Category Name</label>
            <input
                type="text"
                id="cat_name"
                name="cat_name"
                value="<?php echo $editMode
                    ? htmlspecialchars($category['cat_name'])
                    : ''; ?>"
                minlength="2"
                required
            >

            <button type="submit" class="btn-primary">
                <?php echo $editMode ? 'Update Category' : 'Add Category'; ?>
            </button>

            <?php if ($editMode): ?>
                <a href="category.php" class="btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="admin-card">
        <h2>All Categories</h2>

        <?php if (count($categories) > 0): ?>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($categories as $item): ?>
                            <tr>
                                <td><?php echo (int) $item['cat_id']; ?></td>
                                <td><?php echo htmlspecialchars($item['cat_name']); ?></td>
                                <td>
                                    <a
                                        class="edit-link"
                                        href="category.php?edit_id=<?php echo (int) $item['cat_id']; ?>"
                                    >
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No categories have been added yet.</p>
        <?php endif; ?>
    </section>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
?>