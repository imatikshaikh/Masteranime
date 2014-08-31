<?php
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);

$trans = $environment->getTranslator();
?>

<?php if ($paginator->getLastPage() > 1): ?>
    <div class="pagination n_pagination">
        <ul>
            <li><a href="#">Page <?php echo $paginator->getCurrentPage() ?>
                    of <?php echo $paginator->getLastPage() ?></a>
            </li>
            <?php
            if ($paginator->getCurrentPage() > 1)
                echo $presenter->getPrevious($trans->trans('pagination.previous'));
            if ($paginator->getLastPage() == $paginator->getCurrentPage())
                echo $presenter->getNext($trans->trans('pagination.next'));
            ?>
        </ul>
    </div>
<?php endif; ?>