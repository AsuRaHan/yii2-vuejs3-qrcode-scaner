<?php

/** @var yii\web\View $this */


use yii\helpers\Url;
$this->title = 'My Yii Application';
$urlqr = Url::toRoute(['site/code']);
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>
        
    </div>

    <div class="body-content">

<img src="<?= $urlqr ?>">

    </div>
</div>
