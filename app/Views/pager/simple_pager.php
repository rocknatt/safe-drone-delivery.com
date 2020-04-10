<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" style="display: flex;">
    <ul class="pagination m-auto" style="margin: auto;">
    <?php if ($pager->hasPrevious()) : ?>
        <li>
            <a href="<?= $pager->getFirst() ?>" aria-label="First">
                <span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span>
            </a>
        </li>
        <li>
            <a href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                <span aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <li <?= $link['active'] ? 'class="active"' : '' ?>>
            <a href="<?= $link['uri'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li>
            <a href="<?= $pager->getNext() ?>" aria-label="Previous">
                <span aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
            </a>
        </li>
        <li>
            <a href="<?= $pager->getLast() ?>" aria-label="Last">
                <span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>