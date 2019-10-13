<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="SnContent">
    <form action="" class="SnForm" method="get">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-braille"></i> Roles
                <div class="SnGrid m-2 l-4">
                    <div class="SnForm-item">
                        <label for="filterUserID" class="SnForm-label">Usuario</label>
                        <select name="filter[userId]" id="filterUserID" class="SnForm-select">
                            <option value="">Filtrar por usuario</option>
                            <?php foreach ($user ?? [] as $row ): ?>
                                <option value="<?= $row['user_id'] ?>"><?= $row['user_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item">
                        <label for="filterFrom" class="SnForm-label">Desde</label>
                        <input type="date" name="filter[from]" id="filterFrom" class="SnForm-input">
                    </div>
                    <div class="SnForm-item">
                        <label for="filterTo" class="SnForm-label">Hasta</label>
                        <input type="date" name="filter[to]" id="filterTo" class="SnForm-input">
                    </div>
                    <div class="SnForm-item">
                        <label for="filterFolderId" class="SnForm-label">Cliente</label>
                        <select name="filter[folderId]" id="filterFolderId" class="SnForm-select">
                            <option value="">Filtrar por cliente</option>
                            <?php foreach ($customer ?? [] as $row ): ?>
                                <option value="<?= $row['pass_folder_id'] ?>"><?= $row['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="SnToolbar-right">
                <button type="submit" class="SnBtn primary">Filtrar</button>
                <a href="<?= URL_PATH ?>/admin/customer/report" class="SnBtn">Mostrar todo</a>
            </div>
        </div>
    </form>

    <div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnTable-wrapper">
                    <table class="SnTable">
                        <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Password</th>
                            <th>Fecha</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                            <th>Usuario</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($passPassword['data'] ?? [] as $row): ?>
                            <tr>
                                <td><?= $row['customer'] ?></td>
                                <td><?= $row['pass_title'] ?></td>
                                <td><?= $row['create_at'] ?></td>
                                <td><?= $row['table_action'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $row['user_name'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $currentPage = $passPassword['current'] ?? 1;
                $totalPage = $passPassword['pages'] ?? 1;
                $limitPage = $passPassword['limit'] ?? 10;
                $additionalQuery = '';
                $linksQuantity = 3;

                if ($totalPage > 1){
                    $lastPage       = $totalPage;
                    $startPage      = (( $currentPage - $linksQuantity ) > 0 ) ? $currentPage - $linksQuantity : 1;
                    $endPage        = (( $currentPage + $linksQuantity ) < $lastPage ) ? $currentPage + $linksQuantity : $lastPage;

                    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

                    $class      = ( $currentPage == 1 ) ? "disabled" : "";
                    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . ( $currentPage - 1 ) . $additionalQuery . '" class="SnPagination-link">Anterior</a></li>';

                    if ( $startPage > 1 ) {
                        $htmlPaginate   .= '<li class="SnPagination-item"><a href="?limit=' . $limitPage . '&page=1' . $additionalQuery . '" class="SnPagination-link">1</a></li>';
                        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
                    }

                    for ( $i = $startPage ; $i <= $endPage; $i++ ) {
                        $class  = ( $currentPage == $i ) ? "active" : "";
                        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . $i . $additionalQuery . '" class="SnPagination-link">' . $i . '</a></li>';
                    }

                    if ( $endPage < $lastPage ) {
                        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
                        $htmlPaginate   .= '<li><a href="?limit=' . $limitPage . '&page=' . $lastPage . $additionalQuery . '" class="SnPagination-link">' . $lastPage . '</a></li>';
                    }

                    $class      = ( $currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
                    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="?limit=' . $limitPage . '&page=' . ( $currentPage + 1 ) . $additionalQuery . '" class="SnPagination-link">Siguiente</a></li>';

                    $htmlPaginate       .= '</ul></nav>';

                    echo  $htmlPaginate;

                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php' ?>
