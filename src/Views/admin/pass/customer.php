<div class="PassCustomer">
    <div class="PassCustomer-row">
        <input 
            type="text"
            onkeyup="PassCustomerForm.search(event)"
            id="searchPassCustomer"
            style="height: 2.5rem"
            class="SnForm-input icon-search"
            placeholder="Buscar..."
        >
    </div>
    <div class="PassCustomer-row PassCustomer-options">
        <strong>
            Clientes
        </strong>
        <div class="SnBtns">
            <div class="SnBtn jsPassCustomerOption" id="passCustomerModalReload" onclick="InfinityLoading.reload()" >Actualizar</div>
            <div class="SnBtn primary jsPassCustomerOption" onclick="PassCustomerForm.showModalCreate()" >Nuevo</div>
        </div>
    </div>
    <div class="PassCustomer-list">
        <ul class="CustomerList InfiniteScroll" id="passCustomerList">
        </ul>
        <div class="SnLoading"></div>
    </div>
    <div class="PassCustomer-footer">

    </div>
</div>
<?php
    require_once __DIR__ . '/customerModalForm.php';
?>
