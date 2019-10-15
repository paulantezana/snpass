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

    setSearch(search){
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
            PassPasswordForm.setLoading(true);

            // init fetch
            await RequestApi.fetch('/api/passPassword/scroll', {
                method: 'POST',
                body: {
                    current: this.current + 1,
                    search: this.search,
                }
            }).then(res => {
                if (res.success) {
                    this.current = res.current;
                    this.more = res.more;
                    this.renderContent(res);
                }else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(()=>{
                this.loading = false;
                this.loadingChangeState();
                PassPasswordForm.setLoading(false);
            });
        }
    },

    renderContent (response){
        let currentData = response.result || [];

        currentData.forEach((item, index) => {
            let itemEl = document.createElement('li');
            itemEl.classList.add('CustomerList-item');

            let shortName = item.folder_name.substring(0,3);
            itemEl.innerHTML = `<div class="CustomerRow" >
                                    <div class="CustomerRow-data" onclick="PassPasswordForm.id(${item.pass_password_id})">
                                        <div class="CustomerRow-avatar">
                                            <div class="SnAvatar">${shortName}</div>
                                        </div>
                                        <div>
                                            <div class="CustomerRow-name">${item.folder_name} - ${item.title}</div>
                                            <div class="CustomerRow-description">${item.description}</div>
                                        </div>
                                        <div class="CustomerRow-lastUpdate">
                                            <div class="CustomerRow-name">Último uso</div>
                                            <div class="CustomerRow-description" >${item.last_update}</div>
                                        </div>
                                    </div>
                                    <div class="CustomerRow-action">
                                        <div class="SnBtn jsPassPasswordOption"onclick="PassPasswordForm.showModalUpdate(${item.pass_password_id},'${item.folder_name} - ${item.title}')">
                                            <i class="icon-edit"></i>
                                        </div>
                                        <div class="SnBtn jsPassPasswordOption" onclick="PassPasswordForm.delete(${item.pass_password_id},'${item.folder_name} - ${item.title}')">
                                            <i class="icon-trash"></i>
                                        </div>
                                    </div>
                                </div>`;

            this.container.appendChild(itemEl);
        });
    }
};

let PassPasswordForm = {
    currentModeForm : 'create',
    modalName : 'passPasswordModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    passCustomerId : 0,

    init(){
        InfinityLoading.init('#passPasswordList');
        this.currentForm = document.getElementById('passPasswordForm');
        this.submitButton = document.getElementById('passPasswordFormSubmit');
    },

    id(id){
        this.passCustomerId = id;
        this.setLoading(true);
        RequestApi.fetchText('/api/passPassword/detail?passPasswordId=' + id )
            .then(res => {
                let passPasswordContainer =  document.getElementById('passPasswordContainer');
                if (passPasswordContainer){
                    passPasswordContainer.innerHTML = res;
                    SnModal.open('passPasswordModal');
                }
            }).finally(e=>{
            this.setLoading(false);
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

    search(event){
        InfinityLoading.setSearch(event.target.value);
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

        this.setLoading(true);
        let url = '/api/passPassword/actionAudit';
        RequestApi.fetch(url,{
            method: 'POST',
            body: {
                tableAction: 'copiar',
                passPasswordId: passPasswordId,
            }
        }).then(res => {
            SnMessage.success({content: 'Se copio al portapapeles'});
        }).finally(e=>{
            this.setLoading(false);
        })
    },

    submit(event){
        event.preventDefault();

        let passCustomer = {
            folderName: document.getElementById('passFolderName').value || '',
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
        const url = this.currentModeForm === 'update' ? '/api/passPassword/update' : '/api/passPassword/create';

        RequestApi.fetch(url,{
            method: 'POST',
            body: passCustomer
        }).then(res => {
            if (res.success){
                InfinityLoading.reload();
                SnModal.close(this.modalName);
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e =>{
            this.setLoading(false);
        })
    },

    delete(passPasswordId, content = ''){
        let _setLoading = this.setLoading;

        SnModal.confirm({
            title: '¿Estás seguro de eliminar este registro?',
            content: content,
            okText: 'Si',
            okType: 'error',
            cancelText: 'No',
            onOk(){
                _setLoading(true);
                RequestApi.fetch('/api/passPassword/delete',{
                    method: 'POST',
                    body: {
                        passPasswordId: passPasswordId || 0
                    }
                }).then(res => {
                    if (res.success){
                        InfinityLoading.reload();
                        SnMessage.success({ content: res.message });
                    }else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
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

        RequestApi.fetch('/api/passPassword/id',{
            method: 'POST',
            body: {
                passPasswordId: passPasswordId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('passPasswordId').value = res.result.pass_password_id;
                document.getElementById('passPasswordTitle').value = res.result.title;
                document.getElementById('passFolderName').value = res.result.folder_name;
                document.getElementById('passPasswordDescription').value = res.result.description;
                document.getElementById('passPasswordUserName').value = res.result.user_name;
                document.getElementById('passPasswordPassword').value = res.result.password;
                document.getElementById('passPasswordWebSite').value = res.result.web_site;
                document.getElementById('passPasswordKeyChar').value = res.result.key_char;

                SnModal.open(this.modalName);
            }else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e=>{
            this.setLoading(false);
        })
    },
};

document.addEventListener('DOMContentLoaded',()=>{
    PassPasswordForm.init();
});