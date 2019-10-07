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
                        <label for="filterUserID" class="SnForm-label">Desde</label>
                        <input type="date" name="filter[from]" id="filterUserID" class="SnForm-input">
                    </div>
                    <div class="SnForm-item">
                        <label for="filterUserID" class="SnForm-label">Hasta</label>
                        <input type="date" name="filter[to]" id="filterUserID" class="SnForm-input">
                    </div>
                    <div class="SnForm-item">
                        <label for="filterUserID" class="SnForm-label">Cliente</label>
                        <select name="filter[customerId]" id="filterUserID" class="SnForm-select">
                            <option value="">Filtrar por cliente</option>
                            <?php foreach ($customer ?? [] as $row ): ?>
                                <option value="<?= $row['pass_customer_id'] ?>"><?= $row['name'] ?></option>
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
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php' ?>
