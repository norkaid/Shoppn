<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../Controllers/ProductController.php';



require_admin();

$controller = new ProductController();

$editMode = false;
$brand = null;

if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($editId && $editId > 0) {
        $brand = $controller->getBrandById($editId);

        if ($brand) {
            $editMode = true;
        } else {
            $_SESSION['error'] = 'Brand not found.';
        }
    }
}

$brands = $controller->getAllBrands();

include __DIR__ . '/../layout/header.php';
?>

<div class="admin-container">
    <section class="admin-card">
        <h2><?php echo $editMode ? 'Edit Brand' : 'Add Brand'; ?></h2>

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
                ? '../../actions/update_brand_action.php'
                : '../../actions/add_brand_action.php'; ?>"
            method="POST"
        >
            <?php if ($editMode): ?>
                <input
                    type="hidden"
                    name="brand_id"
                    value="<?php echo (int) $brand['brand_id']; ?>"
                >
            <?php endif; ?>

            <label for="brand_name">Brand Name</label>
            <input
                type="text"
                id="brand_name"
                name="brand_name"
                value="<?php echo $editMode
                    ? htmlspecialchars($brand['brand_name'])
                    : ''; ?>"
                minlength="2"
                required
            >

            <button type="submit" class="btn-primary">
                <?php echo $editMode ? 'Update Brand' : 'Add Brand'; ?>
            </button>

            <?php if ($editMode): ?>
                <a href="brand.php" class="btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="admin-card">
        <h2>All Brands</h2>

        <?php if (count($brands) > 0): ?>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Brand Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($brands as $item): ?>
                            <tr>
                                <td><?php echo (int) $item['brand_id']; ?></td>
                                <td><?php echo htmlspecialchars($item['brand_name']); ?></td>
                                <td>
                                    <a
                                        class="edit-link"
                                        href="brand.php?edit_id=<?php echo (int) $item['brand_id']; ?>"
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
            <p>No brands have been added yet.</p>
        <?php endif; ?>
    </section>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
?>