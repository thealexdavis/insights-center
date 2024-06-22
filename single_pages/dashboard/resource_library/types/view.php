<?php

defined("C5_EXECUTE") or die("Access Denied.");

echo '<div class="btn_row" style="margin-bottom: 25px;"><a href="/index.php/dashboard/resource_library/types/add" class="btn btn-primary">ADD RESOURCE TYPE</a></div>';

if (empty($resources)) {
    echo "<h3>There are no resource types at this time.</h3>";
} else {
    ?>
<div id="ccm-search-results-table">
    <table class="ccm-search-results-table" data-search-results="resourceTypes">
        <thead>
            <tr>
                <?php foreach ($columns as $column) { ?>
                <th data-q="<?php echo $column["dbTitle"]; ?>">
                    <?php echo $column["columnTitle"]; ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $resource) {
        echo "<tr>";
        foreach ($columns as $column) {
            if ($column["dbTitle"] !== "actions") {
                echo "<td class='ccm-search-results-name'>";
                echo $resource[$column["dbTitle"]];
            }
            if ($column["dbTitle"] == "actions") {
                echo "<td>";
                if(intval($resource['typeDefault']) !== 1){
                    echo '<a href="/index.php/dashboard/resource_library/types/edit/' .
                        $resource["id"] .
                        '" class="btn btn-primary">Edit</a>&nbsp;<a href="/index.php/dashboard/resource_library/types/delete/' .
                        $resource["id"] .
                        '" class="btn btn-danger">Delete</a>';
                }
            }
            echo "</td>";
        }
        echo "</tr>";
    } ?>
        </tbody>
    </table>
</div>
<?php
} ?>