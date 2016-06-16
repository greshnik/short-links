<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Короткие ссылки';
?>
<div class="site-index">
        <div class="input-group">
            <input type="text" class="form-control short-input" placeholder="Введите url">
            <span class="input-group-btn">
                <button data-url="<?php echo Url::to(['site/short']); ?>" class="btn btn-success short-button" type="button">Укоротить</button>
            </span>
        </div>
        <p id="result"></p>
</div>
