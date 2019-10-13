<div class="PassCustomer">
    <div class="PassCustomer-row">
        <input 
            type="text"
            onkeyup="PassPasswordForm.search(event)"
            id="searchPassCustomer"
            style="height: 2.5rem"
            class="SnForm-input icon-search"
            placeholder="Buscar en mi bóveda..."
        >
    </div>
    <div class="PassCustomer-row PassCustomer-options">
        <strong>
            Contraseñas
        </strong>
        <div class="SnBtns">
            <div class="SnBtn jsPassPasswordOption" id="passCustomerModalReload" onclick="InfinityLoading.reload()" >Actualizar</div>
            <div class="SnBtn primary jsPassPasswordOption" onclick="PassPasswordForm.showModalCreate()" >Nuevo</div>
        </div>
    </div>
    <div class="PassCustomer-list">
        <ul class="CustomerList InfiniteScroll" id="passPasswordList">
        </ul>
        <div class="SnLoading"></div>
    </div>
    <div class="PassCustomer-footer">

    </div>
</div>
<?php
    // require_once __DIR__ . '/customerModalForm.php';
?>
