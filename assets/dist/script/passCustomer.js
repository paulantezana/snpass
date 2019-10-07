let PassPasswordForm = {
    currentModeForm : 'create',
    modalName : 'passPasswordModalForm',

    currentForm : document.getElementById('passPasswordForm'),
    submitButton : document.getElementById('passPasswordFormSubmit'),

    loading : false,
    passCustomerId : 0,

    list(id){
        this.passCustomerId = id;
        RequestApi.fetchText('/api/customer/password/list?passCustomerId=' + id )
        .then(res => {
            let passPasswordContainer =  document.getElementById('passPasswordContainer');
            if (passPasswordContainer){
                passPasswordContainer.innerHTML = res;
                SnModal.open('passPasswordModal');
            }
        }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        })
    },

    setLoading(state){
        this.loading = state;
        let jsPassPasswordOption = document.querySelectorAll('.jsPassPasswordOption');

        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
                if(jsPassPasswordOption){
                    jsPassPasswordOption.forEach(item => {
                        item.setAttribute('disabled','disabled');
                    });
                }
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
                if(jsPassPasswordOption){
                    jsPassPasswordOption.forEach(item => {
                        item.removeAttribute('disabled');
                    });
                }
            }
        }
    },

    clearForm(){
        if (this.currentForm){
            this.currentForm.reset();
        }
    },

    copyClipboard(containerId,passPasswordId){
        let range = document.createRange();
        range.selectNode(document.getElementById(containerId));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();

        let url = '/api/customer/password/actionAudit';
        RequestApi.fetch(url,{
            method: 'POST',
            body: {
                tableAction: 'copiar',
                passPasswordId: passPasswordId,
            }
        }).then(res => {
                SnMessage.success({content: 'Se copio al portapapeles'});
            }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        });
    },

    submit(event){
        event.preventDefault();

        let _passCustomerId = this.passCustomerId;

        let passCustomer = {
            passCustomerId: this.passCustomerId,
            title : document.getElementById('passPasswordTitle').value || '',
            description : document.getElementById('passPasswordDescription').value || '',
            userName : document.getElementById('passPasswordUserName').value || '',
            password : document.getElementById('passPasswordPassword').value || '',
            webSite : document.getElementById('passPasswordWebSite').value || '',
            keyChar : document.getElementById('passPasswordKeyChar').value || '',
        };
        if (this.currentModeForm === 'update'){
            passCustomer.passPasswordId  = document.getElementById('passPasswordId').value || 0;
        }

        this.setLoading(true);
        const url = this.currentModeForm === 'update' ? '/api/customer/password/update' : '/api/customer/password/create';

        RequestApi.fetch(url,{
            method: 'POST',
            body: passCustomer
        }).then(res => {
            if (res.success){
                this.list(_passCustomerId);
                SnModal.close(this.modalName);
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ confirm: false, title: 'Algo salió mal', content: err.message })
        }).finally(e =>{
            this.setLoading(false);
        })
    },

    delete(passPasswordId, content = ''){
        let _setLoading = this.setLoading;
        let _passCustomerId = this.passCustomerId;
        let _list = this.list;

        SnModal.confirm({ 
            title: '¿Estás seguro de eliminar este registro?',
            content: content,
            okText: 'Si',
            okType: 'error',
            cancelText: 'No',
            onOk(){
                _setLoading(true);
                RequestApi.fetch('/api/customer/password/delete',{
                    method: 'POST',
                    body: {
                        passPasswordId: passPasswordId || 0
                    }
                }).then(res => {
                    if (res.success){
                        _list(_passCustomerId);
                        SnMessage.success({ content: res.message });
                    }else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).catch(err => {
                    SnModal.error({ title: 'Algo salió mal', content: err.message })
                }).finally(e=>{
                    _setLoading(false);
                })
            }
        });
    },

    showModalCreate(){
        SnModal.open(this.modalName);
        this.clearForm();
        this.currentModeForm = 'create';
    },

    showModalUpdate(passPasswordId,content){
        this.currentModeForm = 'update';
        this.setLoading(true);

        RequestApi.fetch('/api/customer/password/id',{
            method: 'POST',
            body: {
                passPasswordId: passPasswordId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('passPasswordId').value = res.result.pass_password_id;
                document.getElementById('passPasswordTitle').value = res.result.title;
                document.getElementById('passPasswordDescription').value = res.result.description;
                document.getElementById('passPasswordUserName').value = res.result.user_name;
                document.getElementById('passPasswordPassword').checked = res.result.password;
                document.getElementById('passPasswordWebSite').value = res.result.web_site;
                document.getElementById('passPasswordKeyChar').value = res.result.key_char;

                SnModal.open(this.modalName);
            }else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        }).finally(e=>{
            this.setLoading(false);
        })
    },
}

let  InfinityLoading = {
    loading : false,
    more : true,
    current : 0,
    search : '',
    container : null,

    init(container){
        this.container = document.querySelector(container);
        
        // first loading
        this.loadMore();

        // validate
        if (this.container) {
            this.container.addEventListener('scroll',ev=>{
                if (this.container.scrollTop + this.container.clientHeight >= this.container.scrollHeight - 50){
                    this.loadMore();
                }
            });
        }
    },

    reload(){
        this.loading = false;
        this.more = true;
        this.current = 0;
        this.search = '';
        if (this.container){
            this.container.innerHTML = '';
            this.loadMore();
        }
    },

    setSearh(search){
        this.more = true;
        this.current = 0;
        this.search = search;
        if (this.container){
            this.container.innerHTML = '';
            this.loadMore();
        }
    },

    loadingChangeState(){
        if (this.container){
            let loader = this.container.nextElementSibling;
            if (loader){
                if (this.loading){
                    loader.classList.add('visible');
                }else{
                    loader.classList.remove('visible');
                }
            }
        }
    },

    async loadMore() {
        // validate
        if (this.loading || !this.more ) {
            return;
        }

        // change state
        this.loading = true;
        this.more = false;

        // validate
        if (this.container) {
            this.loadingChangeState();
            PassCustomerForm.setLoading(true);

            // init fetch
            await RequestApi.fetch('/api/customer/scroll', {
                method: 'POST',
                body: {
                    current: this.current + 1,
                    search: this.search,
                }
            }).then(response => {
                if (response.success) {
                    this.current = response.current;
                    this.more = response.more;
                    this.renderContent(response);
                }
            }).finally(()=>{
                this.loading = false;
                this.loadingChangeState();
                PassCustomerForm.setLoading(false);
            });
        }
    },

    renderContent (response){
        let currentData = response.result || [];

        currentData.forEach((item, index) => {
            let itemEl = document.createElement('li');
            itemEl.classList.add('CustomerList-item');

            let shortName = item.name.substring(0,3);
            itemEl.innerHTML = `<div class="CustomerRow" >
                                    <div class="CustomerRow-data" onclick="PassCustomerForm.loadPassPassword(${item.pass_customer_id})">
                                        <div class="CustomerRow-avatar">
                                            <div class="SnAvatar">${shortName}</div>
                                        </div>
                                        <div>
                                            <div class="CustomerRow-name">${item.name}</div>
                                            <div class="CustomerRow-description" data-tooltip="Ultima actualización">${item.updated_at}</div>
                                        </div>
                                    </div>
                                    <div class="CustomerRow-action">
                                        <div class="SnBtn jsPassCustomerOption" onclick="PassCustomerForm.showModalUpdate(${item.pass_customer_id},'${item.name}')">
                                            <i class="icon-edit"></i>
                                        </div>
                                        <div class="SnBtn jsPassCustomerOption" onclick="PassCustomerForm.delete(${item.pass_customer_id},'${item.name}')">
                                            <i class="icon-trash"></i>
                                        </div>
                                    </div>
                                </div>`;

            this.container.appendChild(itemEl);
        });
    }
}

let PassCustomerForm = {
    currentModeForm: 'create',
    modalName: 'passCustomerModalForm',

    currentForm: document.getElementById('passCustomerForm'),
    submitButton: document.getElementById('passCustomerFormSubmit'),

    loading: false,

    init(){
        InfinityLoading.init('#passCustomerList');
    },

    setLoading(state){
        this.loading = state;
        let jsPassCustomerOption = document.querySelectorAll('.jsPassCustomerOption');
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
                if(jsPassCustomerOption){
                    jsPassCustomerOption.forEach(item => {
                        item.setAttribute('disabled','disabled');
                    });
                }
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
                if(jsPassCustomerOption){
                    jsPassCustomerOption.forEach(item => {
                        item.removeAttribute('disabled');
                    });
                }
            }
        }
    },

    loadPassPassword(id){
        PassPasswordForm.list(id)
    },

    search(event){
        InfinityLoading.setSearh(event.target.value);
    },

    clearForm(){
        if (this.currentForm){
            this.currentForm.reset();
        }
    },

    submit(event){
        event.preventDefault();

        let passCustomer = {
            name: document.getElementById('passCustomerFormName').value || '',
            description: document.getElementById('passCustomerFormDescription').value || '',
        };
        if (this.currentModeForm === 'update'){
            passCustomer.passCustomerId  = document.getElementById('passCustomerId').value || 0;
        }

        this.setLoading(true);
        const url = this.currentModeForm === 'update' ? '/api/customer/update' : '/api/customer/create';

        RequestApi.fetch(url,{
            method: 'POST',
            body: passCustomer
        }).then(res => {
            if (res.success){
                SnModal.close(this.modalName);
                InfinityLoading.reload();
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ confirm: false, title: 'Algo salió mal', content: err.message })
        }).finally(e =>{
            this.setLoading(false);
        })
    },

    delete(passCustomerId, content = ''){
        let _setLoading = this.setLoading
        SnModal.confirm({ 
            title: '¿Estás seguro de eliminar este registro?',
            content: content,
            okText: 'Si',
            okType: 'error',
            cancelText: 'No',
            onOk(){
                _setLoading(true);
                RequestApi.fetch('/api/customer/delete',{
                    method: 'POST',
                    body: {
                        passCustomerId: passCustomerId || 0
                    }
                }).then(res => {
                    if (res.success){
                        InfinityLoading.reload();
                        SnMessage.success({ content: res.message });
                    }else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).catch(err => {
                    SnModal.error({ title: 'Algo salió mal', content: err.message })
                }).finally(e=>{
                    _setLoading(false);
                })
            }
        });
    },

    showModalCreate(){
        SnModal.open(this.modalName);
        this.clearForm();
        this.currentModeForm = 'create';
    },

    showModalUpdate(passCustomerId,content){
        this.currentModeForm = 'update';
        this.setLoading(true);

        RequestApi.fetch('/api/customer/id',{
            method: 'POST',
            body: {
                passCustomerId: passCustomerId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('passCustomerId').value = res.result.pass_customer_id || '';
                document.getElementById('passCustomerFormName').value = res.result.name || '';
                document.getElementById('passCustomerFormDescription').value = res.result.description || '';
                SnModal.open(this.modalName);
            }else {
                SnModal.confirm({ title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.confirm({ title: 'Algo salió mal', content: err.message })
        }).finally(e=>{
            this.setLoading(false);
        })
    }
}
PassCustomerForm.init();