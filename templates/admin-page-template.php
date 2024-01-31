<!-- templates/admin-page-template.php -->
<div id="woo-discount-managment-section">
    <h1><?php echo $data['title']; ?></h1>
    <form id="woo-discount-managment-form">

        <div class="woo-discount-line">
            <label for="search-cat">Discount category</label>
            <select name="search_cat" id="search-cat">
                <?php foreach($data['category'] as $cat_key => $category): ?>
                    <option value="<?php echo $cat_key; ?>" <?php selected( $cat_key, $data['data']['discount_cat'] ) ?>>
                        <?php echo $category; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="woo-discount-line">
            <label for="search-count">Number of products from this category</label>
            <input required type="number" name="search_count" id="search-count" value="<?php echo $data['data']['discount_count']; ?>" min="1" max="999">
        </div>
        
        <div class="woo-discount-line">
            <label for="search-cat-free">Free products category</label>
            <select name="search_cat_free" id="search-cat-free">
                <?php foreach($data['category'] as $cat_key => $category): ?>
                    <option value="<?php echo $cat_key; ?>" <?php selected( $cat_key, $data['data']['discount_cat_free'] ) ?>>
                        <?php echo $category; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="woo-discount-line-submit">
            <input type="submit" value="Save">
        </div>
    </form>

    
</div>
