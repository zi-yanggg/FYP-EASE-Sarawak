<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>" class="ease-pager-nav">
    <ul class="ease-pager">

        <?php if ($pager->hasPrevious()) : ?>
            <li class="ease-pager__item">
                <a class="ease-pager__link" href="<?= $pager->getFirst() ?>" aria-label="First page" title="First">
                    <i class="bi bi-chevron-double-left"></i>
                </a>
            </li>
            <li class="ease-pager__item">
                <a class="ease-pager__link" href="<?= $pager->getPrevious() ?>" aria-label="Previous page" title="Previous">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="ease-pager__item <?= $link['active'] ? 'ease-pager__item--active' : '' ?>">
                <a class="ease-pager__link <?= $link['active'] ? 'ease-pager__link--active' : '' ?>"
                   href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="ease-pager__item">
                <a class="ease-pager__link" href="<?= $pager->getNext() ?>" aria-label="Next page" title="Next">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            <li class="ease-pager__item">
                <a class="ease-pager__link" href="<?= $pager->getLast() ?>" aria-label="Last page" title="Last">
                    <i class="bi bi-chevron-double-right"></i>
                </a>
            </li>
        <?php endif ?>

    </ul>
</nav>
