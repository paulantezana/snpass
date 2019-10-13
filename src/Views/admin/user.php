<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class="icon-braille"></i> Usuarios
        </div>
        <div class="SnToolbar-right">
<!--                <div class="SnBtn">-->
<!--                    <i class="icon-refresh"></i>-->
<!--                    Actualizar-->
<!--                </div>-->
            <div class="SnBtn primary jsUserOption" onclick="UserForm.showModalCreate()" >
                <i class="icon-plus"></i>
                Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnTable-wrapper">
                <table class="SnTable">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th style="width: 100px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user['data'] ?? [] as $row): ?>
                            <tr>
                                <td>
                                    <div class="SnAvatar">
                                        <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="avatar">
                                    </div>
                                </td>
                                <td><?= $row['user_name'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['state'] ?></td>
                                <td>
                                    <div class="SnTable-action">
                                        <div class="SnBtn jsUserOption" onclick="UserForm.executeUpdatePassword(<?= $row['user_id'] ?>)">
                                            <i class="icon-lock"></i>
                                        </div>
                                        <div class="SnBtn jsUserOption" onclick="UserForm.executeUpdateNormal(<?= $row['user_id'] ?>)">
                                            <i class="icon-edit"></i>
                                        </div>
                                        <div class="SnBtn jsUserOption" onclick="UserForm.delete(<?= $row['user_id'] ?>,'<?= $row['user_name'] ?>')">
                                            <i class="icon-trash"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
                    $currentPage = $user['current'] ?? 1;
                    $totalPage = $user['pages'] ?? 1;
                    $limitPage = $user['limit'] ?? 10;
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
<script src="<?= URL_PATH ?>/assets/dist/script/user-min.js"></script>
<?php
    require_once  __DIR__ . '/userModalForm.php';
    require_once __DIR__ . '/layout/footer.php'
?>
