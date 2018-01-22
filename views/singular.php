<?php $this->layout('base') ?>

<article id="page-<?=$this->e($id)?>" class="entry entry--page">
    <header class="entry__header">
        <h1 class="entry__title"><?=$this->e($title)?></h1>
    </header>

    <div class="entry__content">
        <?=$this->e($content)?>
    </div>

    <?=$this->e($moose)?>
</article>
