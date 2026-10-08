<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Navigasi Halaman">
    <ul class="pagination pagination-sm mb-0">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link shadow-xs" href="<?= $pager->getFirst() ?>" aria-label="Awal">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link shadow-xs" href="<?= $pager->getPrevious() ?>" aria-label="Sebelumnya">
                    <span aria-hidden="true">&lsaquo;</span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link text-muted opacity-50">&laquo;&laquo;</span>
            </li>
            <li class="page-item disabled">
                <span class="page-link text-muted opacity-50">&lsaquo;</span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link shadow-xs fw-semibold" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link shadow-xs" href="<?= $pager->getNext() ?>" aria-label="Selanjutnya">
                    <span aria-hidden="true">&rsaquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link shadow-xs" href="<?= $pager->getLast() ?>" aria-label="Akhir">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link text-muted opacity-50">&rsaquo;</span>
            </li>
            <li class="page-item disabled">
                <span class="page-link text-muted opacity-50">&raquo;&raquo;</span>
            </li>
        <?php endif ?>
    </ul>
</nav>
