<?php
/**
 * Custom Pagination Template
 * Compatible with both local and hosting environments
 */

$pager->setSurroundCount(2);

// Function to preserve per_page parameter in pagination links
function addPerPageParam($url) {
    $perPage = $_GET['per_page'] ?? null;
    if ($perPage) {
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'per_page=' . $perPage;
    }
    return $url;
}
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination justify-content-center">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= addPerPageParam($pager->getFirst()) ?>" class="page-link" aria-label="<?= lang('Pager.first') ?>">
                    <i class="fas fa-angle-double-left"></i>
                    <span class="sr-only"><?= lang('Pager.first') ?></span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= addPerPageParam($pager->getPrevious()) ?>" class="page-link" aria-label="<?= lang('Pager.previous') ?>">
                    <i class="fas fa-angle-left"></i>
                    <span class="sr-only"><?= lang('Pager.previous') ?></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= addPerPageParam($link['uri']) ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= addPerPageParam($pager->getNext()) ?>" class="page-link" aria-label="<?= lang('Pager.next') ?>">
                    <i class="fas fa-angle-right"></i>
                    <span class="sr-only"><?= lang('Pager.next') ?></span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= addPerPageParam($pager->getLast()) ?>" class="page-link" aria-label="<?= lang('Pager.last') ?>">
                    <i class="fas fa-angle-double-right"></i>
                    <span class="sr-only"><?= lang('Pager.last') ?></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>

<style>
.pagination {
    margin: 20px 0;
}

.pagination .page-link {
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    z-index: 2;
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-item:first-child .page-link {
    margin-left: 0;
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.pagination .page-item:last-child .page-link {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
