<!-- templates/admin-page-template.php -->
<div id="text-managment-section">
    <h1>Text Management12</h1>
    <form id="text-manage-form">
        <label for="search-text">Enter Search Text:</label>
        <input type="text" id="search-text" name="search_text" placeholder="keyword...">
        <input type="submit" value="Search">
    </form>

    <div id="text-manage-results">
        <div id="search-info" hidden>Results for <span id="search-info-text"></span><span id="search-nothing" hidden> - no matches found</span></div>
        <div class="results-table">
            <div class="result-column" id="title-column">

                <div class="result-column-header">
                    <h2>Title</h2>
                    <form class="text-manage-form-column" form-type="title" id="text-manage-form-title">
                        <input type="text" id="replace-text-title" name="replace_text"  placeholder="new keyword...">
                        <input type="submit" value="Replace" disabled>
                    </form>
                </div>

                <div class="result-column-items" id="title-results"></div>
            </div> <!-- #title-column -->

            <div class="result-column" id="content-column">

                <div class="result-column-header">
                    <h2>Content</h2>
                    <form class="text-manage-form-column" form-type="content" id="text-manage-form-content">
                        <input type="text" id="replace-text-content" name="replace_text"  placeholder="new keyword...">
                        <input type="submit" value="Replace" disabled>
                    </form>
                </div>

                <div class="result-column-items" id="content-results"></div>
            </div> <!-- #content-column -->

            <div class="result-column" id="meta-title-column">

                <div class="result-column-header">
                    <h2>Meta Title</h2>
                    <form class="text-manage-form-column" form-type="meta-title" id="text-manage-form-meta-title">
                        <input type="text" id="replace-text-meta-title" name="replace_text"  placeholder="new keyword...">
                        <input type="submit" value="Replace" disabled>
                    </form>
                </div>

                <div class="result-column-items" id="meta-title-results"></div>
            </div> <!-- #meta-title-column -->

            <div class="result-column" id="meta-description-column">

                <div class="result-column-header">
                    <h2>Meta Description</h2>
                    <form class="text-manage-form-column" form-type="meta-description" id="text-manage-form-meta-description">
                        <input type="text" id="replace-text-meta-description" name="replace_text"  placeholder="new keyword...">
                        <input type="submit" value="Replace" disabled>
                    </form>
                </div>
                
                <div class="result-column-items" id="meta-description-results"></div>
            </div> <!-- #meta-description-column -->

        </div>
    </div>
</div>
