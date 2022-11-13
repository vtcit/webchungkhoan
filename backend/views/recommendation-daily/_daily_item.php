<?php

use common\models\Recommendation;

    $fields = (new Recommendation())->attributeLabels();
    if(isset($fields['created_date'])) unset($fields['created_date']);
    if(isset($fields['created_at'])) unset($fields['created_at']);
?>
<table class="table border">
    <thead class="bg-info text-light">
        <tr>
            <?php foreach($fields as $name => $label) { ?> 
            <th><?= $label ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $k => $r) { ?>
        <tr>
            <?php foreach($fields as $name => $label) { ?>
                <td>

                    <?php if(isset($r->{$name})) echo $r->{$name}; ?>
                </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>